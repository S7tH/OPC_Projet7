<?php

namespace AppBundle\Repository;

class UserRepository  extends AbstractRepository
{
    public function search($order = 'asc', $limit = 20, $offset = 0, $term)
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u')
            ->orderBy('u.id', $order)
        ;

        if ($term) {
            $qb
                ->where('u.username LIKE ?1')
                ->setParameter(1, '%'.$term.'%')
            ;
        }

        return $this->paginate($qb, $limit, $offset);
    }
}
