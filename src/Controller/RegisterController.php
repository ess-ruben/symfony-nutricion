<?php

namespace App\Controller;

use App\Entity\Core\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RegisterController extends AbstractController
{

    private $em;
    private $security;
    private $tokenStorage;

    public function __construct(
        EntityManagerInterface $em,
        Security $security,
        TokenStorageInterface $tokenStorage
    ) {
        $this->security = $security;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
    }

    #[Route('/heyBoss', name: 'bossRegister')]
    public function bossRegister(Request $request): Response
    {
        $check = $this->em->getRepository(User::class)->findOneBy([]);
        if(!empty($check)){
            throw new BadRequestException("On vas bisen");
        }

        $user = new User();
        
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user->setRoles([User::ROLE_ADMIN]);
            $this->em->persist($user);
            $this->em->flush();

            //return $this->redirectToRoute('admin');
            return $this->authenticateNewUser($user);
        }

        return $this->render('heyBoss/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/register', name: 'userRegister')]
    public function userRegister(Request $request): Response
    {
        $check = $this->em->getRepository(User::class)->findByRole(User::ROLE_BUSINESS);
        if(!empty($check)){
            throw new BadRequestException("On vas bisen");
        }
        
        $user = new User();
        $user->setRoles([User::ROLE_BUSINESS]);
        
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user->setRoles([User::ROLE_BUSINESS]);
            $business = $user->getYourBusiness();
            $user->setYourBusiness(null);
            
            $this->em->persist($user);
            $business->setBossUser($user);
            $this->em->persist($business);
            
            $this->em->flush();

            return $this->authenticateNewUser($user);
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function authenticateNewUser(User $user)
    {
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
        return $this->redirectToRoute('admin');
    }
}
