<?php

namespace Ecommerce\EcommerceBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ProduitsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitsRepository extends EntityRepository
{


    public function findByTableau($array)
    {

        $pb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.id IN (:array)')
            ->setParameter('array', $array);

        return $pb->getQuery()->getResult();
    }


    public function finbyCategorie($categories)
    {

        $pb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.categories= :categories')
            ->andWhere('u.disponible = 1')
            ->orderBy('u.id')
            ->setParameter('categories', $categories);

        return $pb->getQuery()->getResult();

    }


    public function recherche($chaine)
    {

        $pb = $this->createQueryBuilder('u')
            ->select('u')
            ->where('u.nom like :chaine ')
            ->andWhere('u.disponible = 1')
            ->orderBy('u.id')
            ->setParameter('chaine', $chaine);

        return $pb->getQuery()->getResult();

    }
}