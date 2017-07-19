<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DataBundle\Entity\Serie;
use DataBundle\Event\UpdateEpisodesEvent;

class SerieController extends Controller
{
    /**
     * @Route("/serie/{slug}", name="app_serie_info", options = { "expose" = true })
     */
    public function infoAction($slug)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('DataBundle:Serie');
		$serie = $repository->findOneBySlug($slug);
        if($serie->getImportStatus() == Serie::IMPORT_INIT) {
            $serie = $this->get('serieManager')->partialComplete($serie);
        }
        
        $event = new UpdateEpisodesEvent($serie);
        $this->get('event_dispatcher')->dispatch(UpdateEpisodesEvent::NAME, $event);
		return $this->render('AppBundle:Serie:serieInfo.html.twig',
			array('serie'  => $serie));
	}
}
