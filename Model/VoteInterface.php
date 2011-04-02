<?php

namespace Rizza\VotingBundle\Model;

interface VoteInterface
{
    /**
     * Returns true if the vote is an upvote.
     *
     * @return bool
     */
    public function isUpvote();

    /**
     * Returns true if the vote is a downvote.
     *
     * @return bool
     */
    public function isDownvote();

    /**
     * Returns the actual vote value.
     *
     * @return int
     */
    public function getValue();

    /**
     * Returns the owning user.
     *
     * @return Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser();

    /**
     * Returns the subject Votable object.
     *
     * @return Votable
     */
    public function getObject();
}