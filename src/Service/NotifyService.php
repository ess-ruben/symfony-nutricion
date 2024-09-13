<?php

namespace App\Service;

use App\Entity\Client\ResetPassword;
use App\Entity\Core\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class NotifyService
{

  const EMAIL_PASSWORD = 'email/password.twig.html';
  private $em;
  protected $coreService;
  private $translator;
  private $twig;
  private $mailer;

  public function __construct(
    CoreService $coreService,
    TranslatorInterface $translator,
    Environment $twig,
    MailerInterface $mailer,
  ) {
    $this->translator = $translator;
    $this->coreService = $coreService;
    $this->em = $coreService->getEntityManager();
    $this->twig = $twig;
    $this->mailer = $mailer;
  }

  public function sendPush(?string $title, ?string $descrition, $token)
  {
    //$fcmClient = new \RedjanYm\FCMBundle\FCMClient((new \sngrl\PhpFirebaseCloudMessaging\Client())->setApiKey(getEnv("FIREBASE_API_KEY")));
    //$notification = $fcmClient->createDeviceNotification($title, $descrition, $token);
    //$fcmClient->sendNotification($notification);
  }

  public function getTemplatedEmailForgotPassword(ResetPassword $resetPassword)
  {
    $user = $resetPassword->getUser();
    $subject = $this->translator->trans('email.subject.remeberPass');
    $email = $this->generateTemplatedEmail($subject,self::EMAIL_PASSWORD,
      [
        'resetPassword'=>$resetPassword,
        'domain' => 'https://server-api.fly.dev/'
      ]
    );
    $this->sendEmail($email,$user->getEmail());
  }

  private function generateTemplatedEmail(string $subject,string $templatePath,array $params = []) : TemplatedEmail
  {
    return (new TemplatedEmail())
      ->subject($subject)
      //->htmlTemplate('emails/template.html.twig')
      //->context(['body' => $body, 'footer' => $footer])
      ->htmlTemplate($templatePath)
      ->context($params)
    ;
  }

  private function generateTemplatedEmailByBody(string $subject,string $body,array $params = []) : TemplatedEmail
  {
    $template = $this->twig->createTemplate($body);
    $body = $template->render($params);

    return (new TemplatedEmail())
      ->subject($subject)
      ->htmlTemplate('emails/template.html.twig')
      ->context(['body' => $body])
    ;
  }

  public function sendEmail(
    TemplatedEmail $email,
    string $to,
    $from = NULL,
    ?string $replyTo = NULL,
    $attach = NULL,
    ?array $bccs = []
  ) {

    if ($replyTo) {
      $email->replyTo($replyTo);
    }

    if (!empty($bccs) && count($bccs)) {
      foreach ($bccs as $bcc) {
        $email->addCc($bcc);
      }
    }

    if (!empty($attach)) {
      if (is_string($attach)) {
        $email->attachFromPath($attach);
      }
    }

    return $this->sendCommandEmail($email, $to, $from);
  }

  private function sendCommandEmail(TemplatedEmail $email, $to, $from = NULL)
  {
    $currentFrom = "noreply@noreply.com";
    $currentFromName = "noreply";

    if ($bussines = $this->coreService->getBusiness()) {
      $currentFromName = $bussines->getName();
      if ($address = $bussines->getAddress()) {
        $currentFrom = $address->getEmail();
      }
    }

    if (!empty($from)) {
      if (is_array($from)) {

        if (isset($from["email"])) {
          $currentFrom = $from["email"];
        }

        $currentFromName = isset($from["name"]) ? $from["name"] : $currentFrom;
      } else {
        $currentFrom = $from;
        $currentFromName = $from;
      }
    }

    $email->from(
      new Address($currentFrom, $currentFromName)
    ) //->from($currentFrom)
      ->to($to)
    ;

    try {
      $this->mailer->send($email);
      $this->coreService->getLogger()->info("Email: send is --> success");
    } catch (\Exception $e) {
      $this->coreService->getLogger()->info("Email: send is --> failed");
      $this->coreService->getLogger()->info("Email: error --> " . $e->getMessage());
    }

    return $email;
  }

}
