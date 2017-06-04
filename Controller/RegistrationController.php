<?php

namespace EscapeHither\SecurityManagerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EscapeHither\SecurityManagerBundle\Entity\User;
use EscapeHither\SecurityManagerBundle\Form\UserType;
use EscapeHither\SecurityManagerBundle\Form\UserRegistrationType;
use EscapeHither\SecurityManagerBundle\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        // 1) build the form
        $user = new User();
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
            return $this->redirectToRoute('homepage');
        }
        return $this->render(
            'EscapeHitherSecurityManagerBundle:registration:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
