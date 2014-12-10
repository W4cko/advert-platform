<?php

namespace Kev\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends EntityRepository
{
    /**
     * Training, same result of the findAll() method
     */
    public function myFindAll(){
        // Use first letter from the Entity
        // Method 1
        // $queryBuilder = $this->_em->createQueryBuilder()->select('a')->from($this->_entityName, 'a');

        // Method 2 better than the previous
        $queryBuilder = $this->createQueryBuilder('a');

        $query = $queryBuilder->getQuery();
        $results = $query->getResult();

        return $results;
    }

    /**
     * @param $id
     * @return array
     */
    public function myFind($id){
        $queryBuilder = $this->createQueryBuilder('a');

        $queryBuilder
            ->where('a.id = :id')
            ->setParameter('id', $id);


        $results = $queryBuilder
            ->getQuery()
            ->getResult();

        return $results;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return QueryBuilder
     */
    public function whereCurrentYear(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->andWhere('a.date BETWEEN :start AND :end')
            ->setParameter('start', new \Datetime(date('Y').'-01-01'))
            ->setParameter('end',   new \Datetime(date('Y').'-12-31'));
        return $queryBuilder;
    }

    /**
     * @return array
     */
    public function getAdvertWithApplications()
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.applications', 'app')
            ->addSelect('app')
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public  function getAdvertWithCategories()
    {

    }

}
