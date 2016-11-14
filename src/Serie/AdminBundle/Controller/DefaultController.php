<?php

namespace Serie\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SerieAdminBundle:Default:index.html.twig');
    }
}
