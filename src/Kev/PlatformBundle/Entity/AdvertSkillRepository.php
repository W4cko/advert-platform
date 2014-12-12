<?php

namespace Kev\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * AdvertRepository
 *
 */
class AdvertSkillRepository extends EntityRepository
{
    public function findByAdvert($advert){
        $qb = $this->createQueryBuilder('as');
        $qb
            ->where('as.advert = :advert')
            ->setParameter('advert', $advert);

        return $qb->getQuery()->getResult();
    }
}