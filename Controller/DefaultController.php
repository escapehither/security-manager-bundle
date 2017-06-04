<?php

namespace EscapeHither\SecurityManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EscapeHitherSecurityManagerBundle:Default:index.html.twig');
    }
}
