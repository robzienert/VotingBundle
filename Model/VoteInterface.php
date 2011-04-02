<?php

namespace Rizza\VotingBundle\Model;

interface VoteInterface
{
    public function isUpvote();

    public function isDownvote();

    public function getUser();

    public function getObject();
}