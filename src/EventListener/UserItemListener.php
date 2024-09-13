<?php

namespace App\EventListener;

use App\Util\Interfaces\UserInterface;
use App\Util\Interfaces\CreatorInterface;
use App\Util\Traits\UserForceItem;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * The user item listener listens for items using the App\Util\Interfaces\UserInterface trait.
 * It sets the user of the object depending on the current user
 *
 * @author ruben
 */
class UserItemListener
{

    /**
    * @var TokenStorageInterface
    */
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }
    /**
    * @ORM\PrePersist
    */
    public function prePersist(LifecycleEventArgs $event)
    {
        $userLogged = $this->tokenStorage->getToken();
        if (empty($userLogged) || empty($userLogged->getUser()))
        {
            return;
        }

        $entity = $event->getObject();
        if($entity instanceof UserInterface && $entity->getForceUser())
        {
            $user = null;
            try {
                $user = $entity->getUser();
            } catch (\Throwable $th) {
                $user = $userLogged->getUser();
            }
            
            $entity->setUser($user);
        }

        if($entity instanceof CreatorInterface && $entity->getForceCreator())
        {
            if (empty($entity->getCreator())) {
                $entity->setCreator($userLogged->getUser());   
            }
        }

    }
}