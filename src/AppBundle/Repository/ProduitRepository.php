<?php

namespace AppBundle\Repository;

class ProduitRepository extends AbstractRepository
{
    public function search($term, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('p')
            ->select('p')
            ->orderBy('p.title', $order)
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