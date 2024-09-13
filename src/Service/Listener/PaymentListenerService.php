<?php

namespace App\Service\Listener;

use App\Entity\Payment\Payment;
use App\Service\PaymentService;
use Doctrine\ORM\Event\LifecycleEventArgs;

class PaymentListenerService extends PaymentService
{
  
  public function handlePaymentPreEvent(Payment $payment,LifecycleEventArgs $event)
  {
    $isNew = is_null($payment->getId());
  }

  public function handlePaymentPostEvent(
    Payment $payment,
    LifecycleEventArgs $event,
    string $eventName = 'postPersist')
  {
    $isNew = $eventName == 'postPersist';
  }

}
