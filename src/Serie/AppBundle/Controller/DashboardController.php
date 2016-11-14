<?php
namespace Serie\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Serie\DataBundle\Entity\Serie;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="serie_app_dashboard")
     */
	public function indexAction()
    {
		$user = $this->get('security.token_storage')->getToken()->getUser();
		
		$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
		$collecteds = $repository_collected->findByUser($user);
		$totalSeenDuration = $this->totalSeenDuration($collecteds);
		$totalToSeeDuration = $this->totalToSeeDuration($collecteds);
		return $this->render('SerieAppBundle:Dashboard:dashboard.html.twig',
			array(	'user'  => $user,
					'totalSeenDuration' => $totalSeenDuration,
					'totalToSeeDuration' => $totalToSeeDuration,
					));
    }
	

	public function newEpisodesAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		
		$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
		$collectedsNextEpisode = $repository_collected->findByUser($user);
		$collectedsNextSaison = $collectedsNextEpisode;
		
		usort($collectedsNextEpisode, array($this,'compareNextEpisodeFirstAired'));
		usort($collectedsNextSaison, array($this,'compareNextSaisonFirstAired'));
		return $this->render('SerieAppBundle:Dashboard:listNewEpisode.html.twig',
			array('collectedsNextEpisode'  => $collectedsNextEpisode,
					'collectedsNextSaison'  => $collectedsNextSaison));
	}
	
	public function seriesSuggestionAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		$series = $repository->findSuggestion($user->getId());
		
		return $this->render('SerieDataBundle:Serie:seriesSuggestion.html.twig',
			array('list_series'  => $series));
	}
	
	public function seriesToSeeAction()
	{
		$user = $this->get('security.token_storage')->getToken()->getUser();
		
		$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
		$collecteds = $repository_collected->findByUser($user);
		
		return $this->render('SerieAppBundle:Dashboard:seriesToSee.html.twig',
			array('collecteds'  => $collecteds));
	}
	
	public function programAction()
	{
		$today = new \DateTime('now');
		$idDay = date('w');
		
		$user = $this->get('security.token_storage')->getToken()->getUser();
		return $this->render('SerieAppBundle:Dashboard:program.html.twig',
			array('user'  => $user,
					'day' => $idDay));
	}
	
	public function totalSeenDuration($collecteds)
	{
		$totalDuration = 0;
		foreach($collecteds  as $key => $collected)
		{
			$totalDuration += $collected->getSeenDuration();
		}
		// return $totalDuration;
		return (floor($totalDuration / 60))."h".($totalDuration % 60)."m";
	}
	public function totalToSeeDuration($collecteds)
	{
		$totalDuration = 0;
		foreach($collecteds  as $key => $collected)
		{
			$totalDuration += $collected->getToSeeDuration();
		}
		// return $totalDuration;	
		return (floor($totalDuration / 60))."h".($totalDuration % 60)."m";		
	}
	
	public function compareNextEpisodeFirstAired($collectedA, $collectedB)
	{
			if(!$collectedA->getSerie()->getNextEpisode())
				return 1;
			else if(!$collectedB->getSerie()->getNextEpisode())
				return -1;
			if ($collectedA->getSerie()->getNextEpisode()->getFirstAired() == $collectedB->getSerie()->getNextEpisode()->getFirstAired()) {
				return 0;
			}
			return ($collectedA->getSerie()->getNextEpisode()->getFirstAired() < $collectedB->getSerie()->getNextEpisode()->getFirstAired()) ? -1 : 1;
	}
	
	public function compareNextSaisonFirstAired($collectedA, $collectedB)
	{
			if(!$collectedA->getSerie()->getNextSaison())
				return 1;
			else if(!$collectedB->getSerie()->getNextSaison())
				return -1;
			if ($collectedA->getSerie()->getNextSaison()->getFirstAired() == $collectedB->getSerie()->getNextSaison()->getFirstAired()) {
				return 0;
			}
			return ($collectedA->getSerie()->getNextSaison()->getFirstAired() < $collectedB->getSerie()->getNextSaison()->getFirstAired()) ? -1 : 1;
	}
}