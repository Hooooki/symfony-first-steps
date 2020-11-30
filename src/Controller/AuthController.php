<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{

    /**
     * @Route("/register", name="auth.register")
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function register(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, Request $request) : Response {

        $user = new User();

        $registerForm = $this->createForm(RegisterType::class, $user);

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid())
        {

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('auth/register.html.twig', [

            'registerForm' => $registerForm->createView()

        ]);

    }

    /**
     * @Route("/login", name="auth.login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils) : Response
    {

        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('auth/login.html.twig', [

            "error" => $error

        ]);

    }

    /**
     * @Route("/logout", name="auth.logout")
     * @return Response
     */
    public function logout() : Response {

    }

}
