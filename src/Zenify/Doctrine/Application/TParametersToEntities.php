<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\Application;

use Nette\Application\BadRequestException;
use Nette\Reflection\ClassType;
use Nette\Utils\Strings;
use Nette\DI\Container;
use Kdyby;
use Kdyby\Doctrine\EntityManager;


trait TParametersToEntities
{
	/** @var Container */
	private $container;

	/** @var EntityManager */
	private $entityManager;


	public function injectParametrToEntities(Container $container, EntityManager $entityManager = NULL)
	{
		$this->container = $container;
		$this->entityManager = $entityManager;
	}


	/**
	 * @param  string
	 * @param  array
	 * @return bool
	 */
	protected function tryCall($method, array $parameters)
	{
		$rc = $this->getReflection();
		if ($rc->hasMethod($method)) {
			$rm = $rc->getMethod($method);
			if ($rm->isPublic() && !$rm->isAbstract() && !$rm->isStatic()) {
				$this->checkRequirements($rm);
				$args = $rc->combineArgs($rm, $parameters);

				if (Strings::match($method, '~^(action|render|handle).+~')) {
					$args = $this->replaceParametersByEntity($rm->parameters, $args);
				}

				$rm->invokeArgs($this, $args);
				return TRUE;
			}
		}

		return FALSE;
	}


	/**
	 * @return mixed[]
	 */
	private function replaceParametersByEntity(array $methodParameters, array $args)
	{
		foreach ($methodParameters as $i => $parameter) {
			if ($className = $parameter->className) {
				$rc = ClassType::from($className);
				if ($rc->is('Kdyby\Doctrine\Entities\BaseEntity') && $args[$i]) {
					$args[$i] = $this->findById($className, $args[$i]);
				}

			}
		}

		return $args;
	}


	/**
	 * @param string
	 * @param int
	 * @return object|null
	 * @throws  BadRequestException
	 */
	private function findById($entityName, $id)
	{
		$entity = $this->getEntityManager()->find($entityName, $id);

		if ($entity == NULL) {
			throw new BadRequestException("Value '$id' not found in collection '$entityName'.");
		}

		$this->getContainer()->callInjects($entity);
		return $entity;
	}


	/**
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		if ($this->entityManager == NULL && $this->parent) {
			if ($em = $this->parent->context->getByType('Kdyby\Doctrine\EntityManager')) {
				$this->entityManager = $em;
			}
		}

		return $this->entityManager;
	}


	/**
	 * @return Container|SystemContainer
	 */
	public function getContainer()
	{
		if ($this->container == NULL) {
			$this->container = $this->presenter->getContext();
		}

		return $this->container;
	}

}
