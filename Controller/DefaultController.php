<?php

namespace Rizza\VotingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RizzaVotingBundle:Default:index.html.twig');
    }
}
