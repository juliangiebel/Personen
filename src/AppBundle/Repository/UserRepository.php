<?php
/**
 * Created by PhpStorm.
 * User: giebelj
 * Date: 23.05.18
 * Time: 14:35
 */

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function getAllOrderedBy($field){
        return $this->base()
            ->orderBy('u.'.$field)
            ->getQuery()->getResult();
    }

    public function getByEmail($email){
        $qb = $this->base();
        $qb->where($qb->expr()->eq('email',$email));

        return $qb->getQuery()->getResult();

    }

    public function getById($id){
        $qb = $this->base();
        $qb->where($qb->expr()->eq('id',$id));

        return $qb->getQuery()->getResult();
    }

    public function findByOrdered($search, $term, $order){
        $qb = $this->base();
        $qb->where($qb->expr()->eq($term,$search))
            ->orderBy('u.'.$order);

        return $qb->getQuery()->getResult();

    }

    private function base(){
        return $this->getEntityManager()->createQueryBuilder()
            ->select(array(
                'u.id',
                'u.title',
                'u.name',
                'u.surname',
                'u.birthday',
                'u.email',
                'u.postCode',
                'u.city',
                'u.street',
            ))
            ->from('AppBundle:AppUser', 'u');
    }
}