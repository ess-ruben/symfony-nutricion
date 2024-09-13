<?php

namespace App\EntityListener\Client;

use App\Entity\Calendar\Meeting;
use App\Entity\Client\UserMeasure;
use App\Service\CoreService;
use App\Util\Enum\MeetingStatus;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of UserMeasureListener
 *
 * @author Ruben
 */
class UserMeasureListener
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
     * @ORM\PrePersist
     */
    public function prePersist(UserMeasure $entity, LifecycleEventArgs $event)
    {
        if ($meeting = $entity->getMeeting()) {
            $entity->setUser($meeting->getUser());
            $entity->setBusiness($meeting->getBusiness());
        }
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(UserMeasure $entity, LifecycleEventArgs $event)
    {
        if ($meeting = $entity->getMeeting()) {
            $meeting->setStatus(MeetingStatus::COMPLETE);
            $this->em->persist($meeting);
            $this->em->flush();
        }
    }

}