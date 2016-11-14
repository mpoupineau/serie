<?php
namespace Serie\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Serie\DataBundle\Entity\Serie;
use Serie\DataBundle\Entity\Episode;
use Serie\DataBundle\Entity\Actor;

class DefaultController extends Controller
{
	
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
                        'content' => $this->renderView('SerieDataBundle:Serie:displayNewSerie.html.twig', array('list_series'  => $series))
                    ));
            }
            return $response;
			// }
		}
		return $this->render('SerieAppBundle:Default:allSerie');
	}
	
    public function homeAction()
    {
		return $this->render('SerieAppBundle::layout.html.twig');
    }
    public function addSeriesAction()
    {

		
        return $this->render('SerieAppBundle:Default:index.html.twig');
    }
}