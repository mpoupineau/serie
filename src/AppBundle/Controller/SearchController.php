<?php

namespace AppBundle\Controller;

/**
 * Description of SearchController
 *
 * @author Matthieu
 */

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use DataBundle\Entity\Serie;


class SearchController extends Controller {
    
    /**
     * @Route("/get_serie", name="app_search_get_serie", options = { "expose" = true })
     */
    public function getSerieAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			$series = $this->get('serieManager')->initSeries($request->get('serie_name'));
            
			$response = new JsonResponse();
				
            if(gettype($series) == "integer") {
                $response->setData(array(
                    'nb_serie' => $series,
                    'content' => 'Soyez plus précis, '.$series.' séries ont été trouvées'
                ));
            }
            else if(count($series) == 0) 			// Aucune série trouvée
                $response->setData(array(
                    'nb_serie' => count($series),
                    'content' => 'Aucune série "'.$request->get('serie_name').'" n\'a été trouvée'
                ));
            else { 								// Plusieurs série ont été trouvée
                $response->setData(array(
                        'nb_serie' => count($series),
                        'content' => $this->renderView('DataBundle:Serie:displayNewSerie.html.twig', array('list_series'  => $series))
                    ));
            }
            return $response;
		}
		return $this->render('AppBundle:Default:allSerie');
	}
}
