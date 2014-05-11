<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\DI;

use Kdyby;
use Nette;
use Nette\DI\ServiceDefinition;
use Nette\Utils\Validators;


class Extension extends Nette\DI\CompilerExtension
{
	/** @var [] */
	private $defaults = [
		'userEntity' => 'App\Entities\User'
	];


	public function loadConfiguration()
	{
		$this->compiler->addExtension('annotations', new Kdyby\Annotations\DI\AnnotationsExtension)
			->addExtension('console', new Kdyby\Console\DI\ConsoleExtension)
			->addExtension('doctrine', new Kdyby\Doctrine\DI\OrmExtension)
			->addExtension('doctrineforms', new Kdyby\DoctrineForms\DI\FormsExtension)
			->addExtension('events', new Kdyby\Events\DI\EventsExtension)
			->addExtension('validator', new Kdyby\Validator\DI\ValidatorExtension);

		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		Validators::assert($config['userEntity'], 'string');
		$builder->getDefinition('nette.userStorage')
			->setClass('Zenify\Doctrine\Http\UserStorage')
			->addSetup('setEntity', [$config['userEntity']]);

		$builder->addDefinition($this->prefix('inject.dao.event'))
			->setClass('Zenify\Doctrine\Events\CallInjectOnDaoCreate')
			->addTag(Kdyby\Events\DI\EventsExtension::TAG_SUBSCRIBER);
	}

}
