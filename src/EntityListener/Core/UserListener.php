<?php

namespace App\EntityListener\Core;

use App\Entity\Core\User;
use App\Service\UserService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of UserListener
 *
 * @author Ruben
 */
class UserListener
{

    private $hasher;
    private $coreService;
    private $userService;

    public function __construct(
        UserPasswordHasherInterface $hasher,
        UserService $userService
    )
    {
        $this->hasher = $hasher;
        $this->coreService = $userService->coreService;
        $this->userService = $userService;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(User $userItem, LifecycleEventArgs $event)
    {
        if (!empty($userItem->getPlainPassword())) {
            // Create user
            $userItem->setPassword($this->hasher->hashPassword($userItem, $userItem->getPlainPassword()));
        }

        if(!$userItem->isUserAdmin() && !$userItem->isBossBusiness()){
            if ($business = $this->coreService->getBusiness()) {
                $userItem->setBusiness($business);
            }
        }
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(User $userItem, LifecycleEventArgs $event)
    {
        $this->userService->checkCalendarUser($userItem);
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(User $userItem, LifecycleEventArgs $event)
    {
        // Change password
        if (!empty($userItem->getPlainPassword())) {
            $userItem->setPassword($this->hasher->hashPassword($userItem, $userItem->getPlainPassword()));
        }
    }

}