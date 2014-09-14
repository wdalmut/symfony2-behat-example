<?php

namespace Corley\Bundle\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CorleyBaseBundle:Default:index.html.twig', array('name' => $name));
    }
}
