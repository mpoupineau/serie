<?php
namespace Serie\DataBundle\Controller;

use Serie\DataBundle\Form\getFormCollectedForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Serie\DataBundle\Entity\Collected;
use Serie\DataBundle\Entity\Serie;
use Serie\DataBundle\Form\CollectedType;

class CollectedController extends Controller
{
	public function getFormAction(Request $request)
    {		
		if ($request->isMethod('POST')) {
			$serie_id = $request->get('serie_id');
			
			$repository_serie = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
			$serie = $repository_serie->findOneById($serie_id);
			$nbEpisodesBySeason = $this->getNbEpisodesBySeason($serie);
			
			if($request->get('user_id') > 0) // Update
			{
				$repository_user = $this->getDoctrine()->getManager()->getRepository('SerieUserBundle:User');
				$user = $repository_user->findOneById($request->get('user_id'));
			
				$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
				$collected = $repository_collected->findOneBy(array('serie' => $serie, 'user' => $user));
				$form = $this->createForm(CollectedType::class, $collected);
				return $this->render('SerieDataBundle:Collected:modalForm.html.twig',
				array('serie'  => $serie,
						'form' => $form->createView(),
						'rated' => $collected->getRated(),
						'nbSeasonSeen' => $collected->getSeasonSeen(),
						'nbEpisodeSeen' => $collected->getEpisodeSeen(),
						'nbEpisodesBySeason' => $nbEpisodesBySeason,
						'typeForm' => 'update'
				));
			}
			
			
			$collected = new Collected();
			$form = $this->createForm(CollectedType::class, $collected);
			
			 return $this->render('SerieDataBundle:Collected:modalForm.html.twig',
				array('serie'  => $serie,
						'form' => $form->createView(),
						'rated' => 0,
						'nbSeasonSeen' => 0,
						'nbEpisodeSeen' => 0,
						'nbEpisodesBySeason' => $nbEpisodesBySeason,
						'typeForm' => 'add'
				));
		}
    }
	
	public function addCollectedAction(Request $request)
    {		
		if ($request->isMethod('POST')) {
			
			$repository_serie = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
			$serie = $repository_serie->findOneById($request->get('serie_id'));
			
			$repository_user = $this->getDoctrine()->getManager()->getRepository('SerieUserBundle:User');
			$user = $repository_user->findOneById($request->get('user_id'));
				
			$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
			$collected = $repository_collected->findOneBy(array('serie' => $serie, 'user' => $user));
			
			$em = $this->getDoctrine()->getManager();
			if(!$collected)
			{				
				$collected = new Collected();
				$collected->setRated($request->get('rating'));
				$collected->setSeasonSeen($request->get('seasonSeen'));
				$collected->setEpisodeSeen($request->get('episodeSeen'));
				$collected->setComment($request->get('comment'));
				if($request->get('follow') == "false")
					$collected->setFollow(false);
				if($request->get('alertEachEpisode') == "true")
					$collected->setAlertEachEpisode(true);
				if($request->get('alertFirstEpisode') == "true")
					$collected->setAlertFirstEpisode(true);
				if($request->get('alertLastEpisode') == "true")
					$collected->setAlertLastEpisode(true);
				
				$serie->addCollected($collected);
							
				$user->addCollected($collected);
				$collected->updateStatus();
				$em->persist($collected);
				
				$em->flush();
				$response = new JsonResponse();
				$response->setData(array(
					'title' => 'Ajout',
					'content' => 'la série <b>'.$serie->getName().'</b> a été ajouté dans votre collection',
					'css_status' => $collected->getStatus()
				));
				return $response;
				// return new Response("la série <b>".$serie->getName()."</b> a été ajouté dans votre collection");
			}
			else { //update
				$collected->setRated($request->get('rating'));
				$collected->setSeasonSeen($request->get('seasonSeen'));
				$collected->setEpisodeSeen($request->get('episodeSeen'));
				$collected->setComment($request->get('comment'));
				$collected->setFollow(false);
				$collected->setAlertEachEpisode(false);
				$collected->setAlertFirstEpisode(false);
				$collected->setAlertLastEpisode(false);
				if($request->get('follow') == "true")
					$collected->setFollow(true);
				if($request->get('alertEachEpisode') == "true")
					$collected->setAlertEachEpisode(true);
				if($request->get('alertFirstEpisode') == "true")
					$collected->setAlertFirstEpisode(true);
				if($request->get('alertLastEpisode') == "true")
					$collected->setAlertLastEpisode(true);
				
				$status_before = $collected->getStatus();
				$collected->updateStatus();
				$em->persist($collected);
				
				$em->flush();
				$response = new JsonResponse();
				$response->setData(array(
					'title' => 'Modification',
					'content' => 'la série <b>'.$serie->getName().'</b> a été modifié',
					'css_status_before' => $status_before,
					'css_status_after' => $collected->getStatus()
				));
				
				return $response;
				
			}
				// return new Response("la série <b>".$serie->getName()."</b> fait déjà partie de votre collection");
			// return $this->render('SerieDataBundle:Collected:modalForm.html.twig',
				// array('serie'  => $serie,
						// 'form' => $form->createView()
				// ));
		}
    }
	
	public function deleteCollectedAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			
			
			if($request->get('confirmation') == "false")
			{
				$repository_serie = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
				$serie = $repository_serie->findOneById($request->get('serie_id'));
				
				$repository_user = $this->getDoctrine()->getManager()->getRepository('SerieUserBundle:User');
				$user = $repository_user->findOneById($request->get('user_id'));
					
				$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
				$collected = $repository_collected->findOneBy(array('serie' => $serie, 'user' => $user));
				
				return $this->render('SerieDataBundle:Collected:modalDeleteConfirmation.html.twig',
					array(	'serie'  => $serie,
							'collected_id' => $collected->getId()));
			}
			else {
				$em = $this->getDoctrine()->getManager();

				$repository_collected = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Collected');
				$collected = $repository_collected->findOneById($request->get('collected_id'));
				
				$serie = $collected->getSerie();
				$user = $collected->getUser();
			
				$serie->removeCollected($collected);
				$user->removeCollected($collected);
				
				$em->remove($collected);
				
				$em->flush();
				$response = new JsonResponse();
				$response->setData(array(
					'title' => 'Suppression',
					'content' => 'la série <b>'.$serie->getName().'</b> a été supprimé de votre collection',
					'css_status' => $collected->getStatus()
				));
				return $response;
				// return new Response("la série <b>".$serie->getName()."</b> a été supprimé de votre collection");
			}
		}
	}
	
	public function getNbEpisodesBySeason($serie)
	{
		$nbEpisodesBySeason[0] = 0;
		for($i = 1;$i <= $serie->getNbSeasons(); $i++)
		{
			$nbEpisodesBySeason[$i] = $serie->getNbEpisodesInSeason($i);
		}
		return $nbEpisodesBySeason;
	}

}