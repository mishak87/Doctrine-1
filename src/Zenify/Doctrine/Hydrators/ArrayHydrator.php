<?php

/**
 * This file is part of Zenify
 *
 * Copyright (c) 2012 Tomas Votruba (http://tomasvotruba.cz)
 *
 * For the full copyright and license information, please view
 * the file license.md that was distributed with this source code.
 */

namespace Zenify\Doctrine\Hydrators;

use Nette;


class ArrayHydrator extends Nette\Object
{

	/**
	 * @return []
	 */
	public static function hydrateEntity($entity)
	{
		$reflection = new \ReflectionClass($entity);
		$data = [];
		foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
			if ( ! $property->isStatic()) {
				$value = $entity->{$property->getName()};

				if ($value instanceof Kdyby\Doctrine\Entities\BaseEntity) {
					$value = $value->getId();

				} elseif ($value instanceof ArrayCollection || $value instanceof PersistentCollection) {
					$value = array_map(function ($item) {
						return $item->id;
					}, $value->toArray());

				} elseif (is_array($value)) {
					$value = array_keys($value);
				}

				$data[$property->getName()] = $value;
			}
		}

		return $data;
	}

}
