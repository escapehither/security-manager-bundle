<?php

/**
 * This file is part of the Genia package.
 * (c) Georden Gaël LOUZAYADIO
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Date: 09/03/17
 * Time: 21:15
 */
namespace EscapeHither\SecurityManagerBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use EscapeHither\SecurityManagerBundle\Form\LoginForm;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $formFactory;
    private $em;
    private $router;
    private $passWordEncoder;
    private $class;

    public function __construct(FormFactoryInterface $formFactory , EntityManager $em, RouterInterface $router,UserPasswordEncoder $passWordEncoder,$class)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passWordEncoder = $passWordEncoder;
        $this->class = $class;
    }

    public function getCredentials(Request $request)
    {

        $isLoginSubmit =$request->getPathInfo() =='/login' && $request->isMethod('POST');
        if (!$isLoginSubmit) {
            // skip authentication
            return;
        }
        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);

        // TODO: see if it's needed to add check if the form is valid.

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );
        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];
        return $this->em->getRepository($this->class)
          ->findOneBy(['email' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->passWordEncoder->isPasswordValid($user, $password)) {

            return true;
        }
        return false;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('escape_hither_security_login');
    }
  
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue

        //$url = '/';
        $home_name =$this->router->match('/');
        $url = $this->router->generate($home_name['_route']);

        if ($request->getSession() instanceof SessionInterface) {
            $attributes = $request->getSession()->all();
            if(!empty($attributes['_security.main.target_path'])){
                if($attributes['_security.main.target_path'] != $this->router->generate('escape_hither_security_login')){
                    $url = $attributes['_security.main.target_path'];
                }

            }
        }

        return new RedirectResponse($url);

    }



}