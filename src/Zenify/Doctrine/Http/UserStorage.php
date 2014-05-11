<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\Http;

use Doctrine\ORM\EntityManager;
use Nette;
use Nette\Http\Session;


class UserStorage extends Nette\Http\UserStorage
{
	/** @var string */
	private $entity;

	/** @var EntityManager */
	private $entityManager;



	public function  __construct(Session $session, EntityManager $entityManager)
	{
		parent::__construct($session);
		$this->entityManager = $entityManager;
	}


	/**
	 * @return Nette\Security\IIdentity
	 */
	public function getIdentity()
	{
		$identity = parent::getIdentity();
		if ($identity && $identity->id) {
			return $this->entityManager->getReference($this->entity, $identity->id);
		}

		return $identity;
	}


	public function setEntity($entity)
	{
		$this->entity = $entity;
	}

}
