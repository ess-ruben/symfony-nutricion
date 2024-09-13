<?php

namespace App\Service;

use App\Entity\Core\User;
use App\Entity\Calendar\CalendarUser;
use Doctrine\ORM\EntityManagerInterface;

class UserService {

  protected $em;
  public $coreService;

  public function __construct(CoreService $coreService)
  {
      $this->coreService = $coreService;
      $this->em = $coreService->getEntityManager();
  }

  public function getCalendarUser(User $user) : ?CalendarUser
  {
      return $this->em->getRepository(CalendarUser::class)->findOneBy(
          ['user'=> $user],
          ['id' => 'DESC']
      );
  }

  public function checkCalendarUser(User $user,$flush = true)
  {
      if(!$user->isClient()){
          return null;
      }

      $calendar = $this->getCalendarUser($user);

      if(empty($calendar)){
        $calendar = new CalendarUser();
        $calendar->setUser($user);
      }else{
        $flush = false;
      }

      if($flush){
        $this->em->persist($calendar);
        $this->em->flush();
      }

      return $calendar;
  }

      
}
