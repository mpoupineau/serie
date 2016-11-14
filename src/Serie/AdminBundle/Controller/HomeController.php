<?php
namespace Serie\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Serie\DataBundle\Entity\Serie;
use Serie\UserBundle\Entity\User;

class HomeController extends Controller
{
	public function homeAction() 
	{
		return $this->render('SerieAdminBundle:Home:home.html.twig');
	}
	
	public function seriesToDeleteAction()
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		$series = $repository->findToDelete();
		
		return $this->render('SerieDataBundle:Serie:seriesSuggestion.html.twig',
			array('list_series'  => $series));
	}
	
	public function numbersAction() 
	{
		$repository_serie = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		$series = $repository_serie->findAll();
		
		$repository_user = $this->getDoctrine()->getManager()->getRepository('SerieUserBundle:User');
		$users = $repository_user->findAll();
		
		$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
		$collecteds = $repository_collected->findAll();
		
		$moy_collected_per_user = count($collecteds) / count($users);
		return $this->render('SerieAdminBundle:Home:numbers.html.twig',
			array(	'nb_series'  => count($series),
					'nb_users' => count($users),
					'moy_collected_per_user' => round($moy_collected_per_user,1)));
	}
	
}