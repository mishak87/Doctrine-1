<?php

/**
 * This file is part of Zenify
 *
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Zenify\Doctrine\Entities;

use Kdyby;
use Zenify\Doctrine\Hydrators\ArrayHydrator;


abstract class BaseEntity extends Kdyby\Doctrine\Entities\BaseEntity
{

	/**
	 * @return []
	 */
	public function toArray()
	{
		return ArrayHydrator::hydrateEntity($this);
	}

}
