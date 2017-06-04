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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;

class JwtTokenAuthenticator extends AbstractGuardAuthenticator
{

    private $jwtEncoder;
    private $em;
    private $router;
    private $passWordEncoder;
    private $class;

    public function __construct(JWTEncoderInterface $jwtEncoder, EntityManager $em, RouterInterface $router,UserPasswordEncoder $passWordEncoder,$class)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->em = $em;
        $this->router = $router;
        $this->passWordEncoder = $passWordEncoder;
        $this->class = $class;
    }


    public function getCredentials(Request $request)
    {
        $extractor = new AuthorizationHeaderTokenExtractor(
          'Bearer',
          'Authorization'
        );
        $token = $extractor->extract($request);

        if (!$token) {
            return;
        }
        return $token;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {

        try {
            $data = $this->jwtEncoder->decode($credentials);

        } catch (JWTDecodeFailureException $e) {
            // if you want to, use can use $e->getReason() to find out which of the 3 possible things went wrong
            // and tweak the message accordingly
            // https://github.com/lexik/LexikJWTAuthenticationBundle/blob/05e15967f4dab94c8a75b275692d928a2fbf6d18/Exception/JWTDecodeFailureException.php

            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        if ($data === false) {
            throw new CustomUserMessageAuthenticationException('Invalid Token');
        }
        $username = $data['username'];
        return $this->em->getRepository($this->class)->findOneBy(['email' => $username]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
      /*  $data = $this->jwtEncoder->decode($credentials);
        $password = $data['password'];
        if ($this->passWordEncoder->isPasswordValid($user, $password)) {

            return true;
        }
        return false;*/
    }

    public function onAuthenticationFailure(
      Request $request,
      AuthenticationException $exception
    ) {
        // TODO: Implement onAuthenticationFailure() method.
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
        return false;
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
    }

    public function start(Request $request, AuthenticationException $authException = null) {
        // called when authentication info is missing from a
        // request that requires it
        return new JsonResponse([
            'error' => 'auth required'
        ], 401);
    }


}