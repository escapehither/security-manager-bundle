<?php

namespace StarterKit\SecurityManagerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('StarterKitSecurityManagerBundle:Default:index.html.twig');
    }
}
