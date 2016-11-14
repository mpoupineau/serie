<?php

namespace Serie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SerieUserBundle:Default:index.html.twig');
    }
}
