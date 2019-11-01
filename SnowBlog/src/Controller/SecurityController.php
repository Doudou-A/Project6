<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = $this->getUser();

        if (!$user) {
            $user = new User();
        }

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setDateCreated(new \DateTime());
            $user->setConfirm(false);

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('sendMail' ,[
                'email' => $user->getEmail()
            ]);
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/security/{email}", name="sendMail")
     */
    public function sendMail(User $user, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Mail de Confirmation'))
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody('Voici le lien pour confirmer votre compte : </br>
            http://localhost:8000/security/confirm/'.$user->getId().''
        ,'text/html');

        $mailer->send($message);

        return $this->redirectToRoute('blog');
    }

    /**
     * @Route("/security/confirm/{id}", name="confirmMail")
     */
    public function confirmation(User $user, ObjectManager $manager)
    {
        $user->setConfirm(true);
        $manager->persist($user);
        $manager->flush();

        return $this->render('security/mailConfirm.html.twig');
    }

    /** 
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        $error = $authUtils->getLastAuthenticationError();

        $lastUsername = $authUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        throw new \Exeption('This sould never be reached!');
    }
}
