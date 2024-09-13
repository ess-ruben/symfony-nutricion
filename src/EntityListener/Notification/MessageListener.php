<?php

namespace App\EntityListener\Notification;

use App\Entity\Notification\Message;
use App\Service\Listener\NotifyListenerService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of MessageListener
 *
 * @author Ruben
 */
class MessageListener
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
    public function postPersist(Message $entity, LifecycleEventArgs $event)
    {
        $this->service->createNotificationUsers($entity);
    }

}