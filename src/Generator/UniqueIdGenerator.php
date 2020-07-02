<?php

namespace Gigadrive\Bundle\SymfonyExtensionsBundle\Generator;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;
use Gigadrive\Bundle\SymfonyExtensionsBundle\DependencyInjection\Util;

class UniqueIdGenerator extends AbstractIdGenerator {
	/**
	 * {@inheritdoc}
	 */
	public function generate(EntityManager $em, $entity) {
		$id = Util::getRandomString(128);

		if (null !== $em->getRepository(get_class($entity))->findOneBy(["id" => $id])) {
			$id = $this->generate($em, $entity);
		}

		return $id;
	}
}