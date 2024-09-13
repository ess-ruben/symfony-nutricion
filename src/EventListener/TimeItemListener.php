<?php

namespace App\EventListener;

use App\Util\Interfaces\TimeInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * The time item listener listens for items using the App\Util\Interfaces\TimeInterface trait.
 * It sets creation and updated date to objects before storing new data or updating it.
 *
 * @author ruben
 */
class TimeItemListener
{

    /**
    * @ORM\PrePersist
    */
    public function prePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
        if($entity instanceof TimeInterface && empty($entity->getCreateAt()) )
        {
            $entity->setCreateAt(new \DateTime());
        }

    }

    /**
    * @ORM\PreUpdate
    */
    public function preUpdate(LifecycleEventArgs $event)
    {
      $entity = $event->getObject();
      if($entity instanceof TimeInterface)
      {
          $entity->setUpdatedAt(new \DateTime());
      }
    }
}