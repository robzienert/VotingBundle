<?php

namespace Rizza\VotingBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

interface VoteManagerInterface
{
    /**
     * Get an array containing the total score for $object and the number of
     * votes it has received.
     *
     * @param Votable $object
     * @return array
     */
    public function getScore(Votable $object);

    /**
     * Get an array mapping object ids to their total score and number of votes
     * for each object.
     *
     * @param array $objects An array of Votable objects.
     * @return array
     */
    public function getScores(array $objects);

    /**
     * Record a user's vote on a given object. Only allows a given user to vote
     * once, though that vote may be changed.
     *
     * A zero vote indicates that any existing vote should be removed.
     *
     * @param Votable       $object
     * @param UserInterface $user
     * @param int           $vote
     * @return bool
     */
    public function recordVote(Votable $object, UserInterface $user, $vote);

    /**
     * Get the top N scored objects for a given model.
     *
     * @param string    $class
     * @param int       $limit
     * @param bool      $reversed
     * @return ArrayCollection
     */
    public function getTop($class, $limit = 10, $reversed = false);

    /**
     * Get the bottom N scored objects for a given model.
     *
     * @param string    $class
     * @param int       $limit
     * @return ArrayCollection
     */
    public function getBottom($class, $limit = 10);

    /**
     * Get the vote made on the given Votable object by the given user. Will
     * return false if no matching vote exists.
     *
     * @param Votable       $object
     * @param UserInterface $user
     * @return int|false
     */
    public function getUserVote(Votable $object, UserInterface $user);

    /**
     * Get an array mapping object ids to their score given by a user for each
     * object.
     *
     * @param array         $objects
     * @param UserInterface $user
     * @return array
     */
    public function getUserVotes(array $objects, UserInterface $user);
}