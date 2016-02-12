<?php

namespace Bundles\UserBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class CustomerRepository extends EntityRepository
{
	public function fetchDetailsById( $id, $boolRetunArray = false ) {
		$objQuery = $this->createQueryBuilder('c')
		->select('c')
		->where('c.id = :id')
		->setParameter('id', $id )
		->getQuery();
		if( $boolRetunArray ) {
			return $objQuery->getOneOrNullResult( Query::HYDRATE_ARRAY );
		}
		return $objQuery->getOneOrNullResult();
	}

					
}