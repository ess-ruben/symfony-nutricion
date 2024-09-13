<?php

namespace App\Service\Listener;

use App\Entity\Client\Device;
use App\Entity\Client\IssueResponse;
use App\Entity\Notification\Message;
use App\Entity\Notification\MessageToUser;
use App\Service\NotifyService;
use App\Util\Enum\NotifyType;
use App\Util\Interfaces\NotifyInterface;
use App\Util\Interfaces\UserInterface;

class NotifyListenerService extends NotifyService
{
  public function sendNotifyUser(MessageToUser $messageToUser)
  {
    $user = $messageToUser->getUser();
    if (empty($user)) {
      return;
    }

    $notif = $messageToUser->getMessage();
    if (empty($notif)) {
      return;
    }

    $devices = $this->em->getRepository(Device::class)->findByUser($user);
    $tokens = [];
    if ($devices)
    {
        foreach ($devices as $device)
        {
            if ($device->getToken() == null || $device->getToken() == "(null)"){
                continue;
            }
            $tokens[] = $device->getToken();
        }
        $this->sendPush($notif->getTitle(), $notif->getDescription(), $tokens);
    }
  }
  public function createNotificationUsers(Message $message)
  {
    if(!$message->getUsers()->count()){
      return;
    }
    
    foreach ($message->getUsers() as $user) {
      
      $msgUser = new MessageToUser();
      $msgUser->setUser($user);
      $msgUser->setMessage($message);

      $this->em->persist($msgUser);

    }

    $this->em->flush();
  }
  public function createNotificationMessage(mixed $entity)
  {
    if (!($entity instanceof NotifyInterface)) {
      return;
    }

    $users = [];
    switch ($entity->getNotifyType()) {
      case NotifyType::USER_TYPE:
        if (method_exists($entity,"getUser")) {
          $users = [$entity->getUser()];
        }
        break;
      
      case NotifyType::BUSINESS_TYPE:
        $users = $this->coreService->getClientsBussines();
        break;
      case NotifyType::ISSUE_TYPE:
        if (
          $entity instanceof IssueResponse && 
          !$entity->getUser()->isClient() &&
          !empty($entity->getIssue())
        ) {
          $users = [$entity->getIssue()->getUser()];
        }
        break;
    }

    if (!count($users)) {
      return;
    }

    $message = new Message();
    foreach ($users as $user) {
      $message->addUser($user);
    }

    $message->setEntityId($entity->getId());

    $entityName = $this->em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
    $explode = explode("\\",$entityName);
    $entityName = array_pop($explode);
    $entityName = strtoupper($entityName);

    $message->setType($entityName);
    $message->setTitle(
      $this->translator->trans("notify.title.$entityName")
    );
    $message->setDescription(
      $this->translator->trans("notify.description.$entityName")
    );

    $this->em->persist($message);
    $this->em->flush();
  }
}
