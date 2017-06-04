<?php

namespace EscapeHither\SecurityManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EscapeHither\SecurityManagerBundle\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
class ProfileController extends Controller
{
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!$user) {
            throw new \LogicException(
              'Edit Profile cannot be used without an authenticated user!'
            );
        }
        $edit_form = $this->createForm(EditUserType::class,$user)  ;
        $edit_form->handleRequest($request);
        if ($edit_form->isSubmitted() && $edit_form->isValid()) {

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
        return $this->render('EscapeHitherSecurityManagerBundle:profile:edit.html.twig', array('edit_form' => $edit_form->createView()));
    }
}
