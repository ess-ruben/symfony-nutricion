<?php

namespace App\EntityListener\Client;

use App\Entity\Client\ResetPassword;
use App\Entity\Core\User;
use App\Service\NotifyService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of ResetPasswordListener
 *
 * @author Ruben
 */
class ResetPasswordListener
{
    private $notifyService;
    public function __construct(
        NotifyService $notifyService
    )
    {
        $this->notifyService = $notifyService;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(ResetPassword $entity, LifecycleEventArgs $event)
    {
        $user = $event->getEntityManager()->getRepository(User::class)->findOneBy([
            'email' => $entity->getEmail(),
            'active' => true
        ]);

        if (
            empty($user) || empty($user->getBusiness())
        ) {
            throw new BadRequestException("error.user.notExist");
        }

        if($user->isUserAdmin() || $user->isUserBusiness()){
            throw new BadRequestException("error.user.notExist");
        }

        $entity->setUser($user);
        $entity->setBusiness($user->getBusiness());

        $expired = new \DateTime('now +1 day');
        $entity->setExpiredAt($expired);
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(ResetPassword $entity, LifecycleEventArgs $event)
    {
        $this->notifyService->getTemplatedEmailForgotPassword($entity);
    }

}