<?php
namespace Serie\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Serie\DataBundle\Entity\Serie;

class SerieController extends Controller
{
    /**
     * @Route("/serie/{slug}", name="serie_app_serieinfo")
     */
    public function serieInfoAction($slug)
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		$serie = $repository->findOneBySlug($slug);
        if($serie->getImportStatus() == Serie::IMPORT_INIT) {
            $serie = $this->get('serieManager')->partialComplete($serie);
        }
        
		return $this->render('SerieAppBundle:Serie:serieInfo.html.twig',
			array('serie'  => $serie));
	}
}
