<?php

namespace App\EventListener;

use App\Service\CoreService;
use App\Util\Interfaces\BusinessInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * The user item listener listens for items using the App\Util\Interfaces\UserInterface trait.
 * It sets the user of the object depending on the current user
 *
 * @author ruben
 */
class BusinessItemListener
{

    /**
    * @var CoreService
    */
    private $coreService;

    public function __construct(CoreService $coreService)
    {
        $this->coreService = $coreService;
    }

    /**
    * @ORM\PrePersist
    */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if($entity instanceof BusinessInterface && empty($entity->getBusiness()))
        {
            if ($business = $this->coreService->getBusiness()) {
                $entity->setBusiness($business);
            }   
        }
    }
}