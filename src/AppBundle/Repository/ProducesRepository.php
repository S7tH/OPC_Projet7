<?php

namespace AppBundle\Repository;

class ProducesRepository extends AbstractRepository
{
    public function search($order = 'asc', $limit = 20, $offset = 0, $term)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.id', $order)
        ;

        if ($term) {
            $qb
                ->where('p.title LIKE ?1')
                ->setParameter(1, '%'.$term.'%')
            ;
        }

        return $this->paginate($qb, $limit, $offset);
    }
}