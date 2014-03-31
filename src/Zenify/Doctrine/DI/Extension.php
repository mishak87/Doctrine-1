<?php

/**
 * This file is part of Zenify
 *
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Zenify\Doctrine\DI;

use Kdyby;
use Nette\DI\CompilerExtension;


class Extension extends CompilerExtension
{

	public function loadConfiguration()
	{
		$this->compiler->addExtension('annotations', new Kdyby\Annotations\DI\AnnotationsExtension)
			->addExtension('console', new Kdyby\Console\DI\ConsoleExtension)
			->addExtension('doctrine', new Kdyby\Doctrine\DI\OrmExtension)
			->addExtension('events', new Kdyby\Events\DI\EventsExtension)
			->addExtension('kdybyforms', new Kdyby\DoctrineForms\DI\FormsExtension)
			->addExtension('validator', new Kdyby\Validator\DI\ValidatorExtension);
	}

}
