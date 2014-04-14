<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\Application\UI;

use Nette\Application\BadRequestException;
use Nette\Reflection\ClassType;
use Nette\Utils\Strings;
use Kdyby;


trait TParametersToEntities
{
	/** @inject @var \Nette\DI\Container */
	public $container;

	/** @var Kdyby\Doctrine\EntityManager */
	private $entityManager;


	public function injectEntityManager(Kdyby\Doctrine\EntityManager $entityManager = NULL)
	{
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
		$entity = $this->entityManager->find($entityName, $id);

		if ($entity == NULL) {
			throw new BadRequestException("Value '$id' not found in collection '$entityName'.");
		}

		$this->container->callInjects($entity);
		return $entity;
	}

}
