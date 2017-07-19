<?php
namespace DataBundle\Controller;

use DataBundle\Form\getFormCollectedForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use DataBundle\Entity\Collected;
use DataBundle\Entity\Serie;
use DataBundle\Form\CollectedType;

class CollectedController extends Controller
{
    /**
     * @Route("/form_collected", name="data_collected_get_form", options = { "expose" = true })
     */
	public function getFormAction(Request $request)
    {		
		if ($request->isMethod('POST')) {
            $serie = $this->get('serieService')->find($request->get('serie_id'));
			$nbEpisodesBySeason = $this->getNbEpisodesBySeason($serie);
			
			if($request->get('action') == "update") 
			{
                $user = $this->get('security.token_storage')->getToken()->getUser();
				$collected = $this->get('collectedService')->findOneBy(array('serie' => $serie, 'user' => $user));
				
                $form = $this->createForm(CollectedType::class, $collected);
				return $this->render('DataBundle:Collected:modalForm.html.twig',
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
			
			 return $this->render('DataBundle:Collected:modalForm.html.twig',
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
	
    /**
     * @Route("/add_collected", name="data_collected_add", options = { "expose" = true })
     */
	public function addCollectedAction(Request $request)
    {		
		if ($request->isMethod('POST')) {
			
			$serie = $this->get('serieService')->find($request->get('serie_id'));
			$user = $this->get('security.token_storage')->getToken()->getUser();
			$collected = $this->get('collectedService')->findOneBy(array('serie' => $serie, 'user' => $user));
			
			if(!$collected)
			{				
				$collected = new Collected();
				$collected->populate($request->request->all());
				$collected->setSerie($serie);
                $collected->setUser($user);
                $collected->updateStatus();
                
                $this->get('collectedService')->save($collected);
                
				$response = new JsonResponse();
				$response->setData(array(
					'title' => 'Ajout',
					'content' => 'la série <b>'.$serie->getName().'</b> a été ajouté dans votre collection',
					'css_status' => $collected->getStatus()
				));
				return $response;
            }
			else { //update
                $collected->populate($request->request->all());
				
				$status_before = $collected->getStatus();
				$collected->updateStatus();
				$this->get('collectedService')->save($collected);
                
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
			// return $this->render('DataBundle:Collected:modalForm.html.twig',
				// array('serie'  => $serie,
						// 'form' => $form->createView()
				// ));
		}
    }
	
    /**
     * @Route("/delete_collected", name="data_collected_delete", options = { "expose" = true })
     */
	public function deleteCollectedAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			
			if($request->get('confirmation') == "false")
			{
				$serie = $this->get('serieService')->find($request->get('serie_id'));
				
				$user = $this->get('security.token_storage')->getToken()->getUser();
					
				$repository_collected = $this->getDoctrine()->getManager()->getRepository('DataBundle:Collected');
				$collected = $repository_collected->findOneBy(array('serie' => $serie, 'user' => $user));
				
				return $this->render('DataBundle:Collected:modalDeleteConfirmation.html.twig',
					array(	'serie'  => $serie,
							'collected_id' => $collected->getId()));
			}
			else {
				$collected = $this->get('collectedService')->find($request->get('collected_id'));
				$this->get('collectedService')->remove($collected);
                
				$response = new JsonResponse();
				$response->setData(array(
					'title' => 'Suppression',
					'content' => 'la série <b>'.$collected->getSerie()->getName().'</b> a été supprimé de votre collection',
					'css_status' => $collected->getStatus()
				));
				return $response;
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