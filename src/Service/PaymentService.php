<?php

namespace App\Service;

use App\Entity\Payment\Payment;
use Doctrine\ORM\EntityManagerInterface;

class PaymentService {

  protected $em;
  protected $coreService;

  public function __construct(CoreService $coreService)
  {
      $this->coreService = $coreService;
      $this->em = $coreService->getEntityManager();
  }
      
}
