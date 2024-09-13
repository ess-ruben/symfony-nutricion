<?php

namespace App\EventListener\Kernel;

use App\Entity\Core\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
/**
 * Request interceptor (for applying filters ans similars)
 *
 * @author clara
 */
class OnRequestListener
{

    protected $em;
    protected $tokenStorage;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        //request headers
        $requestHeaders = $event->getRequest()->headers->all();

        $activeFilter = true;

        if ($this->tokenStorage->getToken())
        {
            if ($user = $this->tokenStorage->getToken()->getUser())
            {
                $business = $user->getYourBusiness() ?? $user->getBusiness();

                if(!empty($business)){
                    $bossUser = $business->getBossUser();
                    $filter = $this->em->getFilters()->enable('business_sql_filter');
                    $filter->setParameter('business_sql_filter', $business->getId());
                    $filter->setParameter('user_boss_sql_filter', $bossUser->getId());
                }

                if($user->isClient()){
                    $filter = $this->em->getFilters()->enable('user_sql_filter');
                    $filter->setParameter('user_sql_filter', $user->getId());
                }else{
                    $activeFilter = false;
                }
            }
        }

        if ($activeFilter) {
            $filter = $this->em->getFilters()->enable('active_sql_filter');
            $filter->setParameter('active_sql_filter', 1);
        }
    }

}