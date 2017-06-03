<?php

namespace StarterKit\SecurityManagerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StarterKit\SecurityManagerBundle\Entity\User;
use StarterKit\SecurityManagerBundle\Form\UserType;
use StarterKit\SecurityManagerBundle\Form\UserRegistrationType;
use StarterKit\SecurityManagerBundle\Form\LoginForm;
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
            'StarterKitSecurityManagerBundle:registration:register.html.twig',
            array('form' => $form->createView())
        );
    }
}
