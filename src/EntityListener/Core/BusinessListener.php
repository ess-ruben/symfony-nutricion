<?php

namespace App\EntityListener\Core;

use App\Entity\Core\Business;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of BusinessListener
 *
 * @author Ruben
 */
class BusinessListener
{

    public function __construct()
    {
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(Business $entity, LifecycleEventArgs $event)
    {
        $this->setNameByAddress($entity);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(Business $entity, LifecycleEventArgs $event)
    {
        $this->setNameByAddress($entity);
    }

    private function setNameByAddress(Business $entity)
    {
        if($address = $entity->getAddress()){
            if (empty($entity->getName())) {
                $entity->setName(
                    $address->getName()
                );
            }
        }
    }

}