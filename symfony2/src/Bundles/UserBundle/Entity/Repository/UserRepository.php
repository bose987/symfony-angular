<?php 

namespace Bundles\UserBundle\Entity\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends EntityRepository
{
	public function fetchById( $id ) {
		$qb = $this->createQueryBuilder('b')
		->select('b.name, b.email')
		->where('b.id = :id')
		->setParameter('id', $id) ;
		
		return $qb->getQuery()->getResult();
	}
	
	public function fetchUsers( $limit ) {
		$qb = $this->createQueryBuilder('b')
		->select('b.name, b.email, b.id, b.customer_id');
		
		if (false === is_null($limit))
			$qb->setMaxResults($limit);
		
			return $qb->getQuery()->getResult();
	}
	
	public function fetchByEmailByPassword( $strEmail, $strPassword ) {
		$query = $this->getEntityManager()
		->createQuery(
			'SELECT
				u, c
			FROM
				BundlesUserBundle:User u
				LEFT JOIN u.customer c
			WHERE 
				u.email = :email
				AND u.password = :password'
		)->setParameters( ['email'=> $strEmail, 'password' => $strPassword ] );
		return $query->getSingleResult();
	}
	
	public function fetchByEmail( $strEmail ) {
		$query = $this->getEntityManager()
		->createQuery(
				'SELECT
				u, c
			FROM
				BundlesUserBundle:User u
				LEFT JOIN u.customer c
			WHERE
				u.email = :email'
				)->setParameters( ['email'=> $strEmail ] );
				return $query->getSingleResult();
	}
	
}