<?php

namespace App\EntityListener\Payment;

use App\Entity\Payment\Payment;
use App\Service\Listener\PaymentListenerService;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of PaymentListener
 *
 * @author Ruben
 */
class PaymentListener
{
    private $service;

    public function __construct(
        PaymentListenerService $service
    )
    {
        $this->service = $service;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist(Payment $entity, LifecycleEventArgs $event)
    {
        $this->service->handlePaymentPreEvent($entity,$event);   
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate(Payment $entity, LifecycleEventArgs $event)
    {
        $this->service->handlePaymentPreEvent($entity,$event);
    }

    /**
     * @ORM\PostPersist
     */
    public function postPersist(Payment $entity, LifecycleEventArgs $event)
    {
        $this->service->handlePaymentPostEvent($entity,$event);
    }

    /**
     * @ORM\PostUpdate
     */
    public function postUpdate(Payment $entity, LifecycleEventArgs $event)
    {
        $this->service->handlePaymentPostEvent($entity,$event,'postUpdate');
    }

}