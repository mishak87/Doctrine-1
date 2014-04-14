<?php

/**
 * This file is part of Zenify
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 */

namespace Zenify\Doctrine\DI;

use Kdyby;
use Nette\DI\CompilerExtension;
use Nette\DI\ServiceDefinition;


class Extension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$this->compiler->addExtension('annotations', new Kdyby\Annotations\DI\AnnotationsExtension)
			->addExtension('console', new Kdyby\Console\DI\ConsoleExtension)
			->addExtension('doctrine', new Kdyby\Doctrine\DI\OrmExtension)
			->addExtension('doctrineforms', new Kdyby\DoctrineForms\DI\FormsExtension)
			->addExtension('events', new Kdyby\Events\DI\EventsExtension)
			->addExtension('validator', new Kdyby\Validator\DI\ValidatorExtension);
	}


	public function beforeCompile()
	{
		// @move to onDaoCreate?
		$builder = $this->containerBuilder;
		foreach ($builder->definitions as $definition) {
			if ($this->isDaoDefinition($definition)) {
				$definition->setInject(TRUE);
			}
		}
	}


	/**
	 * @return bool
	 */
	private function isDaoDefinition(ServiceDefinition $definition)
	{
		if ($definition->factory && isset($definition->factory->arguments[0])
			&& $definition->factory->arguments[0] instanceof Nette\DI\Statement
			&& $definition->factory->arguments[0]->entity == '@doctrine.dao') {
				return TRUE;
		}

		return FALSE;
	}

}
