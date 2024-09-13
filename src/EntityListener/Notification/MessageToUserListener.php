<?php

namespace App\EntityListener\Notification;

use App\Entity\Notification\MessageToUser;
use App\Service\Listener\NotifyListenerService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of MessageToUserListener
 *
 * @author Ruben
 */
class MessageToUserListener
{
    private $service;

    public function __construct(
        NotifyListenerService $service
    )
    {
        $this->service = $service;
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(MessageToUser $entity, LifecycleEventArgs $event)
    {
        $this->service->sendNotifyUser($entity);
    }

}