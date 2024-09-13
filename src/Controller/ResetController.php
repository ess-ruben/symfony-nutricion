<?php

namespace App\Controller;

use App\Entity\Client\ResetPassword;
use App\Form\ResetFormType;
use App\Service\CoreService;
use App\Service\NotifyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class ResetController extends AbstractController
{
    private $coreService;
    private $em;
    public function __construct(
        CoreService $coreService
    )
    {
        $this->coreService = $coreService;
        $this->em = $coreService->getEntityManager();
    }

    #[Route('/reset/password/{token}', name: 'resetPassword')]
    public function resetPassword(string $token,Request $request): Response
    {
        //$token = '27dc789bbf5e43d51b17e5431c92e1db';
        $resetPassword = $this->coreService->getEntityManager()->getRepository(ResetPassword::class)->findOneBy([
            'token' => $token,
            'isActive' => true
        ]);

        $dt = new \DateTime('now');

        if (
            empty($resetPassword) ||
            $dt > $resetPassword->getExpiredAt()
        ) {
            return new Response("Url no existe",404);
        }

        $user = $resetPassword->getUser();

        $form = $this->createForm(ResetFormType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $resetPassword->setIsActive(false);
            $this->em->persist($resetPassword);
            $this->em->flush();

            return $this->redirectToRoute('resetSuccess');
        }

        return $this->render('reset/password.html.twig', [
            'form' => $form->createView(),
            'entity' => $resetPassword
        ]);
    }
    #[Route('/reset/success', name: 'resetSuccess')]
    public function success(Request $request) : Response
    {
        return $this->render('reset/success.html.twig');
    }
}
