<?php

namespace Rizza\VotingBundle\Entity;

use Doctrine\ORM\Query\ResultSetMapping;

class VoteRepository extends Doctrine\ORM\EntityRepository
{
    protected $strategy;

    public function findScore($targetEntityTable)
    {
        $rsm = new ResultSetMapping();
        $rsm->addFieldResult('score', 'vote', 'score');
        $rsm->addFieldResult('sum', 'vote', 'sum');

        $sql = sprintf(
            'SELECT COALESCE(SUM(v.vote)) AS score,
                    COALESCE(COUNT(v.vote)) FROM votes v
                INNER JOIN %s t ON v.target_id = t.id
                WHERE t.id = %d',
            $targetEntityTable,
            $targetEntityId);

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }

    public function findTopScoresForEntity($targetClassName, $limit, $reversed)
    {
        $rsm = new ResultSetMapping();
        $rsm->addEntityResult($targetClassName, 't');
        $rsm->addFieldResult('score', 'vote', 'score');

        $sql = sprintf(
            'SELECT SUM(v.vote) AS score FROM votes v
                INNER JOIN %s t ON v.target_id = t.id
                GROUP BY v.target_id
                HAVING score %s 0
                ORDER BY score %s
                LIMIT %d',
            $targetClassName,
            ($reversed ? '<' : '>'),
            ($reversed ? 'ASC' : 'DESC'),
            $limit);

        $query = $this->_em->createNativeQuery($sql, $rsm);

        return $query->getResult();
    }
}