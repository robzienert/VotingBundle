<?php

namespace Rizza\VotingBundle\Model;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class Vote implements VoteInterface
{
    const VOTE_UP   = 1;
    const VOTE_DOWN = -1;
    const VOTE_NULL = 0;

    protected $id;
    
    protected $user;

    protected $object;

    protected $value;

    public function __construct($object, UserInterface $user, $value)
    {
        $this->setObject($object);
        $this->setUser($user);
        $this->setValue($value);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function setValue($value)
    {
        $this->value = (int) $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isUpvote()
    {
        return self::VOTE_UP == $this->value;
    }

    public function isDownvote()
    {
        return self::VOTE_DOWN == $this->value;
    }
}