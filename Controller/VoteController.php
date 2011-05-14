<?php

namespace Rizza\VotingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class VoteController extends Controller
{
    const QUERY_TARGET_KEY = 'target';
    const QUERY_TARGET_ID_KEY = 'tid';

    public function recordAction()
    {
        $request = $this->get('request');

        if (!$request->isXmlHttpRequest()) {
            /* @var @voteManager VoteManagerInterface */
            $voteManager = $this->get('rizza_voting.vote_manager');

            $target = $this->getTarget($voteManager);
            if ($target) {
                $user = $this->get('security.context')->getToken()->getUser();
                $voteManager->recordVote($target,
                                         $user,
                                         $request->query->get('value'));

                return $this->get('response');
            }
        }

        throw new \Exception('Invalid request... needs better exception');
    }

    /**
     * @todo This needs to be moved elsewhere.
     */
    protected function getTarget($voteManager)
    {
        $query = $this->get('request')->query;
        $mapping = $voteManager->getTargetAssociationMapping(
            $query->get(self::QUERY_TARGET_KEY));

        $repository = $this->get('doctrine.orm.entity_manager')->getRepository($mapping['name']);

        $target = $repository->find($query->get(self::QUERY_TARGET_ID_KEY));

        return $target;
    }
}
