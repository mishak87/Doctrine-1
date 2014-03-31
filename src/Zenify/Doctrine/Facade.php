<?php

/**
 * This file is part of Zenify
 *
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Zenify\Doctrine;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette;


abstract class Facade extends Nette\Object
{
	/** @var EntityDao */
	protected $dao;

	/** @var Nette\DI\Container */
	private $container;


	public function __construct(EntityDao $dao, Nette\DI\Container $container)
	{
		$this->dao = $dao;
		$this->container = $container;
	}


	/**
	 * @param  string
	 * @param  strin[]
	 * @return mixed
	 */
	public function __call($name, $args = [])
	{
		if ( ! method_exists($this, $name) && method_exists($this->dao, $name)) {
			$result = call_user_func_array(array($this->dao, $name), $args);

			if (is_array($result)) {
				foreach ($result as $entity) {
					if (is_object($entity)) {
						$this->container->callInjects($entity);
					}
				}
			}

			return $result;
		}
	}


	/**
	 * @return array
	 */
	public function findPairs($criteria, $value = NULL, $orderBy = [], $key = NULL)
	{
		if ( ! is_array($criteria)) {
			$key = $orderBy;
			$orderBy = [$criteria => 'ASC'];
			$value = $criteria;
			$criteria = [];
		}

		return $this->dao->findPairs($criteria, $value, $orderBy, $key);
	}


	/**
	 * @param  int|BaseEntity
	 */
	public function delete($entity)
	{
		if (is_numeric($entity)) {
			$entity = $this->getEntityManager()->find($this->getEntityName(), $entity);
		}

		$this->dao->delete($entity);
	}



	/**
	 * @return string
	 */
	protected function getEntityName()
	{
		return $this->dao->getClassName();
	}

}
