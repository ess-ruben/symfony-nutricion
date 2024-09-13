<?php

namespace App\EntityListener\Client;

use App\Entity\Client\IssueResponse;
use App\Service\CoreService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of IssueResponseListener
 *
 * @author Ruben
 */
class IssueResponseListener
{
    private $coreService;
    private $em;
    public function __construct(
        CoreService $coreService
    )
    {
        $this->coreService = $coreService;
        $this->em = $coreService->getEntityManager();
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(IssueResponse $entity, LifecycleEventArgs $event)
    {
        if (empty($entity->getUser())) {
            return;
        }
        
        if ($issue = $entity->getIssue()) {
            $issue->setIsOpen($entity->getUser()->isClient());
            $this->em->persist($issue);
            $this->em->flush();
        }
    }

}