<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;
use App\Form\RegistrationType;

use Doctrine\Common\Persistence\ObjectManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use App\Form\ResetPasswordType;





class SecurityController extends AbstractController
{
    //////////////////////////////////////////////////ENREGISTRER UN COMPTE////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form =$this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash)
                //->setIsActive(true)
                ->setRole(['ROLE_USER'])
                ->setToken($tokenGenerator->generateToken())
                ->setMemberSince(new \Datetime());

            $manager->persist($user);
            $manager->flush();


            $message = (new \Swift_Message('Email de comfirmation'))
            ->setFrom('Julian17220@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'security/comfirm/mail.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            );
            $mailer->send($message);

            $this->addFlash('alert', 'Un email de comfirmation vous a été envoyé.');
            return $this->redirectToRoute('security_login');
        }

        return $this->render('security\registration.html.twig',[
            'form' =>$form->createView()
        ]);

    }

    /**
     * @Route("/inscription/verifier_email/{id}/{token}", name="comfirm_email")
     */

    /////////////////////////////////////////////////LOG IN & LOG OUT///////////////////////////////////////////////////

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $helper): Response
    {
        return $this->render('security\login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

    //////////////////////////////////////////////RENITIALISATION DU MOT DE PASSE/////////////////////////////////////////////
    /////////////////////////////////////////////////SI OUBLIE//////////////////////////////////////////////////////////////////

    /**
     * @Route("/reset_password", name="reset_password")
     */
    public function resetPassword(Request $request, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator, ObjectManager $manager)
    {
        // création d'un formulaire "à la volée", afin que l'internaute puisse renseigner son mail
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                ]
            ])
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $manager->getRepository(User::class)->findOneByEmail($form->getData()['email']);
            // aucun email associé à ce compte.
            if (!$user) {
                $this->addFlash('warning', 'Cet email n\'existe pas.');
                return $this->redirectToRoute("reset_password");
            }
            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
            $manager->flush();
            $message = (new \Swift_Message('Renouvellement Mot de passe'))
            ->setFrom('Julian17220@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView(
                    'security/resetting/mail.html.twig',
                    ['user' => $user]
                ),
                'text/html'
            );
            $mailer->send($message);
            $this->addFlash('success', 'Un mail va vous être envoyé afin que vous puissiez renouveller votre mot de passe. Le lien que vous recevrez sera valide 12h.');

            return $this->redirectToRoute("security_login");
        }

        return $this->render('security/resetting/request.html.twig', [
            'form' => $form->createView()
        ]);
    }


    // si supérieur à 12h, retourne false
    // sinon retourne false
    private function isRequestInTime(\Datetime $passwordRequestedAt = null)
    {
        if ($passwordRequestedAt === null)
        {
            return false;
        }

        $now = new \DateTime();
        $interval = $now->getTimestamp() - $passwordRequestedAt->getTimestamp();

        $daySeconds = 60 * 60 * 12 ;
        $response = $interval > $daySeconds ? false : $reponse = true;
        return $response;
    }

//    /**
//    * @Route("/reset_password/{id}/{token}", name="resetting")
//    */
    public function resetting(User $user, $token, Request $request, UserPasswordEncoderInterface $encoder, ObjectManager $manager)
    {
        if ($user->getToken() === null || $token !== $user->getToken() || !$this->isRequestInTime($user->getPasswordRequestedAt()))
        {
            throw $this->createAccessDeniedException('Vous n\'avez pas l\'authorisation');
        }

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            // réinitialisation du token à null pour qu'il ne soit plus réutilisable
            $user->setToken(null);
            $user->setPasswordRequestedAt(null);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre mot de passe a été renouvelé.');

            return $this->redirectToRoute('security_login');

        }

        return $this->render('security/resetting/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
