<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use DataBundle\Entity\Serie;
use DataBundle\Entity\Episode;
use DataBundle\Entity\Actor;

class DefaultController extends Controller
{
	
    public function homeAction()
    {
		return $this->render('AppBundle::layout.html.twig');
    }
    public function addSeriesAction()
    {

		
        return $this->render('AppBundle:Default:index.html.twig');
    }
}