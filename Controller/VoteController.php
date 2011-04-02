<?php

namespace Rizza\VotingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoteController extends Controller
{
    public function indexAction()
    {
        return $this->render('RizzaVotingBundle:Vote:index.html.twig');
    }
}
