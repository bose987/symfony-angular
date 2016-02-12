<?php
namespace Bundles\UserBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use Bundles\UserBundle\Entity\Address;
use Doctrine\ORM\Query;

class AddressRepository extends EntityRepository
{
	public function fetchBillingDetailsByCustomerId( $id, $boolRetunArray = false ) {
		$objQuery = $this->createQueryBuilder('b')
		->select('b')
		->join( 'b.customer', 'c' )
		->where('c.id = :customer_id AND b.type = :type' )
		->setParameters(
			array(
				'customer_id' => $id,
				'type' => Address::TYPE_BILLING
			)
		)
		->getQuery();
		if( $boolRetunArray ) {
			return $objQuery->getResult( Query::HYDRATE_ARRAY );
		}
		return $objQuery->getResult();
	}
	
	public function fetchBillingDetailsByIdByCustomerId( $id, $customerId, $boolRetunArray = false ) {
		$objQuery = $this->createQueryBuilder('b')
		->select('b')
		->join( 'b.customer', 'c' )
		->where('c.id = :customer_id AND b.type = :type AND b.id = :id' )
		->setParameters(
				array(
					'customer_id' => $customerId,
					'type' => Address::TYPE_BILLING,
					'id' => $id
				)
		)
		->getQuery();
		if( $boolRetunArray ) {
			return $objQuery->getResult( Query::HYDRATE_ARRAY );
		}
		return $objQuery->getResult();
	}
}