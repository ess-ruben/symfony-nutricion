<?php

namespace App\EventListener;

use App\Service\Listener\NotifyListenerService;
use App\Util\Interfaces\TimeInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * The time item listener listens for items using the App\Util\Interfaces\TimeInterface trait.
 * It sets creation and updated date to objects before storing new data or updating it.
 *
 * @author ruben
 */
class NotifyItemListener
{
    private $nls;
    public function __construct(NotifyListenerService $nls) {
        $this->nls = $nls;
    }

    /**
    * @ORM\PostPersist
    */
    public function postPersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        $this->nls->createNotificationMessage($entity);
    }
}