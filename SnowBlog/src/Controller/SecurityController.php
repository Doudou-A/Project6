<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            $user->setToken(rand());
            $user->setConfirm(false);

            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('mailRegistration', [
                'email' => $user->getEmail()
            ]);
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/security/formEmail", name="formEmailForgot")
     */
    public function formEmail(UserRepository $repo, \Swift_Mailer $mailer, Request $request)
    {

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $repo->findOneByEmail($form["email"]->getData());
            $message = (new \Swift_Message('Mot de passe oublié'))
                ->setFrom('send@example.com')
                ->setTo($user->getEmail())
                ->setBody(
                    'Voici le lien pour confirmer votre compte : </br>
            http://localhost:8000/security/forgotPassword/' . $user->getToken() . '',
                    'text/html'
                );

            $mailer->send($message);

            return $this->redirectToRoute('blog');
        }

        return $this->render('security/formMailForgot.html.twig', [
            'formEmail' => $form->createView()
        ]);
    }

    /**
     * @Route("/security/{email}", name="mailRegistration")
     */
    public function mailRegistration(User $user, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Mail de Confirmation'))
            ->setFrom('send@example.com')
            ->setTo($user->getEmail())
            ->setBody(
                'Voici le lien pour confirmer votre compte : </br>
            http://localhost:8000/security/confirm/' . $user->getToken() . '',
                'text/html'
            );

        $mailer->send($message);

        return $this->redirectToRoute('blog');
    }


    /**
     * @Route("/security/forgotPassword/{token}", name="forgotPassword")
     */
    public function forgotPassword(User $user, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        if ($user == null){
            echo "ok";
        }

        $form = $this->createFormBuilder()
            ->add('password', PasswordType::class)
            ->add('confirmPassword', PasswordType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form["password"]->getData() == $form["confirmPassword"]->getData()) {
                $hash = $encoder->encodePassword($user, $form["password"]->getData());
                $user->setPassword($hash);

                $manager->persist($user);
                $manager->flush();
            }
            else{
            $error1 = true;

            return $this->render('security/forgotPassword.html.twig', [
                'formPassword' => $form->createView(),
                'error1' => $error1
            ]);
            }

            return $this->redirectToRoute('blog');
        }

        $error1 = null;

        return $this->render('security/forgotPassword.html.twig', [
            'formPassword' => $form->createView(),
            'error1' => $error1
        ]);
    }

    /**
     * @Route("/security/confirm/{token}", name="confirmMail")
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
