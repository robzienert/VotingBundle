<?php

namespace Rizza\VotingBundle\Entity;

class EntityException extends \Exception
{
    public static function classNotValidAssociation($className)
    {
        throw new self("A voting association mapping could not be found for `$className`.");
    }
}