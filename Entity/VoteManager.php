<?php

namespace Rizza\VotingBundle\Entity;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Rizza\VotingBundle\Model\VoteManagerInterface;
use Rizza\VotingBundle\Model\Vote;
use Rizza\VotingBundle\Model\InvalidVoteValueException;

class VoteManager implements VoteManagerInterface
{
    const ASSOCIATION_MAPPING_NAME = 'votes';

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
    protected $class;
    protected $repository;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->repository = $em->getRepository($class);

        $metadata = $em->getClassMetadata($class);
        $this->class = $metadata->name;
    }

    /**
     * {@inheritdoc}
     */
    public function recordVote($target, UserInterface $user, $value)
    {
        if (!in_array($value, array(Vote::VOTE_DOWN, Vote::VOTE_NULL, Vote::VOTE_UP))) {
            throw new InvalidVoteValueException('Invalid vote (must be +1/0/-1)');
        }

        $targetMapping = $this->getTargetEntityAssociationMapping($target);

        $vote = $this->getUserVote($target, $user);

        if ($vote) {
            if (Vote::VOTE_NULL == $value) {
                $this->em->remove($vote);
            } else {
                $voteObject->setValue($value);
                $this->em->persist($vote);
            }

            $this->em->flush();
        } else {
            if (Vote::VOTE_NULL != $value) {
                $class = $this->getClass();

                $vote = new $class($object, $user, $value);
                $this->em->persist($vote);
                $this->em->flush();
            }
        }
    }

    public function getTargetAssociationMapping($target)
    {
        $className = get_class($className);
        $associations = $this->em->getMetadataFactory()
            ->getMetadataFor($this->class);

        foreach ($associations as $name => $mapping) {
            if ($className === $mapping['targetEntity']) {
                return $mapping['targetEntity'];
            }
        }

        throw new \OutOfBoundsException(
            "Could not find target entity association mapping for $className");
    }

    public function getScore($target)
    {
        $mapping = $this->getTargetAssociationMapping($target);
        $score = $this->repository->findScore($mapping['table'],
                                              $target->getId());

        return $score;
    }

    public function getScores(array $targets)
    {
        $scores = array();

        foreach ($targets as $target) {
            $scores[$target->getId()] = $this->getScore($target);
        }

        return $scores;
    }

    public function getTop($className, $limit = 10, $reversed = false)
    {
        $tuple = $this->repository->findTopScoresForEntity($className, $limit, $reversed);
        return $tuple;
    }

    public function getBottom($className, $limit = 10)
    {
        return $this->getTop($className, $limit, true);
    }

    public function getVotes($target)
    {
        return $this->repository->findBy(array('target' => $target));
    }

    public function getVote($id)
    {
        return $this->repository->find($id);
    }

    public function getUserVote($target, UserInterface $user)
    {
        $targetMapping = $this->getTargetEntityAssociationMapping($target);

        $vote = $this->repository->findOneBy(array(
            $targetMapping['fieldName'] => $target,
            'user' => $user,
        ));

        return $vote;
    }

    public function getUserVotes(array $targets, UserInterface $user)
    {
        $votes = array();

        foreach ($targets as $target) {
            $votes[$target] = $this->getUserVote($target, $user);
        }

        return $votes;
    }

    public function getClass()
    {
        return $this->class;
    }
}