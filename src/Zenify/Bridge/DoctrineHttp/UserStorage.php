<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Bridge\DoctrineHttp;

use Kdyby;
use Nette;


/**
 * @method setEntity()
 * @method setEntityManager()
 */
class UserStorage extends Nette\Http\UserStorage
{
	/** @var string */
	private $entity;

	/** @var Kdyby\Doctrine\EntityManager */
	private $entityManager;


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

}
