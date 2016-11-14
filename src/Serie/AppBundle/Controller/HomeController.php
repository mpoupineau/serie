<?php
namespace Serie\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="serie_app_homepage")
     */
	public function homeAction()
    {
        $response = $this->get('serieManager')->searchSeries('black');
        //$response = $this->get('serieManager')->downloadSerie('305288');
        $em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('SerieDataBundle:Serie');
        $serie = $repository->find(281);
        //$response = $this->get('serieManager')->downloadPoster($serie);
        return $this->render('SerieAppBundle:Home:layout.html.twig',
			array('response'  => $response));
    }

}