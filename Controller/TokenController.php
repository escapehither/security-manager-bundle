<?php
/**
 * This file is part of the Genia package.
 * (c) Georden Gaël LOUZAYADIO
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * Date: 20/05/17
 * Time: 23:06
 */

namespace EscapeHither\SecurityManagerBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;

class TokenController extends Controller
{
    public function newTokenAction(Request $request)
    {
        $data['user'] = $request->getUser();
        $data['password'] = $request->getPassword();
        //$data['user'] = 'user@example.com';
        //$data['password']= 'password';
        $userClass = $this->getParameter('starter_kit.security.user.class');
        $user = $this->getDoctrine()
          ->getRepository($userClass)
          ->findOneBy(['email' => $data['user']]);

        if (!$user) {
            throw $this->createNotFoundException();
        }
        $isValid = $this->get('security.password_encoder')
          ->isPasswordValid($user, $data['password']);
        if (!$isValid) {
            throw new BadCredentialsException();
        }
        $token = $this->get('lexik_jwt_authentication.encoder')
          ->encode([
            'username' => $user->getUsername(),
            'exp' => time() + 3600 // 1 hour expiration
          ]);
        return new JsonResponse(['token' => $token]);
    }

}