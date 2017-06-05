<?php

namespace EscapeHither\SecurityManagerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EscapeHither\SecurityManagerBundle\Form\UserRegistrationType;

use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        //TODO add a factory to create user.
        $user_provider_class = $this->getParameter('escape_hither.security.user.class');
        $user = new $user_provider_class();
        // TODO define parameter for user registration type.
        $form = $this->createForm(UserRegistrationType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            //$password = $this->get('security.password_encoder')
            //  ->encodePassword($user, $user->getPlainPassword());
            //$user->setPassword($password);

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user
            // add a confirmation route for success.
            $this->addFlash(
              'notice',
              'Your are register!'
            );
            return $this->redirectToRoute('escape_hither_security_login');
        }
        return $this->render(
            'EscapeHitherSecurityManagerBundle:registration:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
