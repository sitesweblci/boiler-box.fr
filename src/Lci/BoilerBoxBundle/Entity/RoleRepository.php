<?php

namespace Lci\BoilerBoxBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends EntityRepository
{
   public function recuperationDesRoles() {
		$queryBuilder = $this->createqueryBuilder('r')
						->select('r.role');
		return $queryBuilder->getQuery()->getScalarResult();
    }
}
