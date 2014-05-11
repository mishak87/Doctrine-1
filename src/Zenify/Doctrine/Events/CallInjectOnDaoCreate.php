<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\Events;

use Kdyby\Doctrine\EntityDao;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Events\Subscriber;
use Nette\DI\Container;


class CallInjectOnDaoCreate implements Subscriber
{
	/** @var Container */
	private $container;


	public function __construct(Container $container)
	{
		$this->container = $container;
	}


	/**
	 * @return string[]
	 */
	public function getSubscribedEvents()
	{
		return ['Kdyby\Doctrine\EntityManager::onDaoCreate'];
	}


	public function onDaoCreate(EntityManager $em, EntityDao $dao)
	{
		$this->container->callInjects($dao);
	}

}
