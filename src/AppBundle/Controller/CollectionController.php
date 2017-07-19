<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DataBundle\Entity\Serie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CollectionController extends Controller
{
    /**
     * @Route("/collection", name="serie_app_collection")
     */
    public function indexAction()
    {		
		return $this->render('AppBundle:Collection:collection.html.twig');
    }
	
    /**
     * @Route("/collection_sorted", name="serie_app_collection_sorted")
     */
    public function seriesSortedAction(Request $request)
    {
		$user = $this->get('security.token_storage')->getToken()->getUser();
		
		$repository_collected = $this->getDoctrine()->getManager()->getRepository('DataBundle:Collected');
		
		if($request->get('type_sort') == "status") {
                    $collecteds = $repository_collected->findByUser($user);
                    usort($collecteds, array($this,'sortByStatus'));
		}
		else if($request->get('type_sort') == "rated") {
			$collecteds = $repository_collected->findBy(
				array('user' => $user), 
				array('rated' => 'DESC')
            );
		}
		else if($request->get('type_sort') == "firstAired") {
			$collecteds = $repository_collected->findByUserOrderBy($user, "firstAired");
		}		
		else if($request->get('type_sort') == "nbSeasons") {
			$collecteds = $repository_collected->findByUser($user);
			usort($collecteds, array($this,'sortByNbSeasons'));
		}
		// $tab_status = $this->getTabStatus($collecteds);
		
		return $this->render('AppBundle:Collection:seriesSorted.html.twig',
			array('collecteds'  => $collecteds,
					'type_sort' => $request->get('type_sort'))
		);
	}
	
	public function getTabStatus($collecteds)
	{
		$tab_status = array();
		for($i = 0;$i < count($collecteds); $i++)
		{
			$tab_status[$i] = $this->getStatus($collecteds[$i]);
		}
		return $tab_status;
	}
	public function sortByStatus($collectedA, $collectedB)
	{
		if($collectedA->getStatus() == $collectedB->getStatus())
			return 0;
		else if($collectedA->getStatus() == "Continuing")
			return -1;
		else if ($collectedA->getStatus() == "Not started" AND $collectedB->getStatus() == "Ended")
			return -1;
		else 
			return 1;
	}
	
	public function sortByNbSeasons($collectedA, $collectedB)
	{
		if($collectedA->getSerie()->getNbSeasons() == $collectedB->getSerie()->getNbSeasons())
			return 0;
		return ($collectedA->getSerie()->getNbSeasons() > $collectedB->getSerie()->getNbSeasons()) ? -1 : 1;
	}
	
	
	public function getStatus($collected)
	{
		if(($collected->getSerie()->getStatus() == "Ended") AND ($collected->getSerie()->getNbSeasons() == $collected->getSeasonSeen())) 
			return 2; // done
		else if($collected->getSeasonSeen() > 0) 
			return 0; // continuing
		else 
			return 1; // to start
		/*if($collected->getSeasonSeen() === 0) 
			return 1; // continuing
		else 
			return 0;*/
	}
}