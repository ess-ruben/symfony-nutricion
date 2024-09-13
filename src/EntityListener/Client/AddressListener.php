<?php

namespace App\EntityListener\Client;

use App\Entity\Client\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of AddressListener
 *
 * @author Ruben
 */
class AddressListener
{

    public function __construct()
    {
        
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(Address $entity, LifecycleEventArgs $event)
    {
        switch ($entity->getAction()) {
            case Address::ADDRESS_PROFILER:
                # code...
                break;

            case Address::ADDRESS_PROFILER:
                # code...
                break;
        }
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(Address $entity, LifecycleEventArgs $event)
    {
        switch ($entity->getAction()) {
            case Address::ADDRESS_PROFILER:
                # code...
                break;

            case Address::ADDRESS_PROFILER:
                # code...
                break;
        }
    }

}