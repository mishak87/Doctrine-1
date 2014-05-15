<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\Application;

use Nette\Application\BadRequestException;
use Nette\Reflection\ClassType;
use Kdyby\Doctrine\EntityManager;


class ParametersToEntities
{
	/** @var EntityManager */
	private $entityManager;


	public function __construct(EntityManager $entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * @return []
	 */
	public function processMethodParameters(array $methodParameters, array $args)
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

		return $entity;
	}

}
