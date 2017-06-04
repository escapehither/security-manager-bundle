<?php
/**
 * This file is part of the Genia package.
 * (c) Georden Gaël LOUZAYADIO
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Date: 08/03/17
 * Time: 23:30
 */

namespace EscapeHither\SecurityManagerBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use EscapeHither\SecurityManagerBundle\Entity\User;
use EscapeHither\SecurityManagerBundle\Form\UserType;
use EscapeHither\SecurityManagerBundle\Form\LoginForm;
use Symfony\Component\HttpFoundation\Request;
class SecurityController extends Controller{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $form = $this->createForm(LoginForm::class, ['_username'=>$lastUsername]);

        return $this->render('EscapeHitherSecurityManagerBundle:security:login.html.twig', array(
            'form' => $form->createView(),
            'error'=> $error,
        ));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logOutAction(){
        throw new \Exception('this should not be reached!');

    }
}