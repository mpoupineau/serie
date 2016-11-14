<?php
namespace Serie\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Serie\DataBundle\Entity\Serie;
use Serie\DataBundle\Entity\Episode;
use Serie\DataBundle\Entity\Actor;

class LibraryController extends Controller
{
    const NB_ELEMENT = 36;
    
    /**
     * @Route("/get_series_filter", name="serie_app_getseriesfilter")
     */
    public function getSeriesFilterAction(Request $request) 
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		if ($request->isMethod('POST')) {
			
			$year_min = $this->getMinFiltre($request->get('years_range'));
			$year_max = $this->getMaxFiltre($request->get('years_range'));
			$rating_min = $this->getMinFiltre($request->get('rating_range'));
			$rating_max = $this->getMaxFiltre($request->get('rating_range'));
			$tab_category = $this->getTab_category($request->get('tab_category'));
			$status = $this->getContinuing($request->get('continuing'));
			if(count($tab_category) == Serie::NB_CATEGORY) {
                $tab_category == null;
            }
            
			$list_Allseries = $repository->findByYearsCategoriesRatingStatus($year_min, $year_max, $tab_category, $rating_min, $rating_max, $status);
			$num_page = $request->get('num_page');
            $changePage = true;
            if($request->get('num_page') < 1) {
                $num_page = 1;
                $changePage = false;
            }
			$max_page = ceil(count($list_Allseries)/$this::NB_ELEMENT);
			if($max_page < $request->get('num_page')) {
				$num_page = $max_page;
            }
            dump($list_Allseries);
			//$list_series = $repository->findByYearsCategoriesRatingStatusPagination($year_min, $year_max, $tab_category, $rating_min, $rating_max, $status, $num_page, $this::NB_ELEMENT);
			$list_series = array_slice($this->get('serieManager')->classifyByPopularity($list_Allseries), ($num_page-1)*$this::NB_ELEMENT, $num_page*$this::NB_ELEMENT);
            $response = new JsonResponse();dump($list_series);
			$response->setData(array(
				'nb_series' => count($list_Allseries),
                'changePage' => $changePage,
				'html' => $this->renderView('SerieAppBundle:Library:seriesFilter.html.twig',array('list_series'  => $list_series, 'page' => $num_page,'max_page' => $max_page))
			));
			return $response;
		}
	
		
		$list_Allseries = $repository->findAll();
        dump($list_Allseries);
        $list_series = array_slice($this->get('serieManager')->classifyByPopularity($list_Allseries), 0, $this::NB_ELEMENT);
		dump($list_series);
        return $this->render('SerieAppBundle:Library:seriesFilter.html.twig',
			array('list_series'  => $list_series,
					'page' => 1,
					'max_page' => ceil(count($list_Allseries)/$this::NB_ELEMENT)));
	}
    
    /**
     * @Route("/all-serie", name="serie_app_seriepage")
     */
	public function allSerieAction()
    {
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		$list_series = $repository->findAll();
		return $this->render('SerieAppBundle:allSerie:allSerie.html.twig',
			array('list_series'  => $list_series));
    }
	
    public function homeAction()
    {
		return $this->render('SerieAppBundle::layout.html.twig');
    }
    public function addSeriesAction()
    {
        return $this->render('SerieAppBundle:Default:index.html.twig');
    }
    
    
    private function getMinFiltre($data_range)
	{
		return substr($data_range,0, strpos($data_range," "));
	}
	
	private function getMaxFiltre($data_range)
	{
		$strTemp = substr($data_range,3);
		return substr($strTemp, strpos($data_range," "));
	}
    
    private function getTab_category($tab_category)
	{
		$tab_category_fr = explode(',', $tab_category);
		$tab_category_en = array();
		if($tab_category == "")
		{
			$tab_category_en[] = "none";
			return $tab_category_en;
		}
		
		$trad_fr_en = array("action" => "Action",
							"animation" => "Animation",
							"aventure" => "Adventure",
							"comédie" => "Comedy",
							"crime" => "Crime", 
							"drame" => "Drama",						 
							"enfant" => "Children",
							"famille" => "Family",
							"fantaisie" => "Fantasy",
							"horreur" => "Horror", 
							"mini-série" => "Mini-Series",
							"mistère" => "Mystery",
							"réalité" => "reality",
							"romance" => "Romance", 
							"science-fiction" => "Science-Fiction",
							"suspense" => "Suspense",
							"thriller" => "Thriller",
							"western" => "Western"
							);
		
		foreach($tab_category_fr as $category_fr)
		{
			$tab_category_en[] = $trad_fr_en[$category_fr];
		}
		
		return $tab_category_en;
	}
    
	private function getContinuing($continuing)
	{
		if($continuing == "oui")
			return "continuing";
		else if($continuing == "non")
			return "ended";
		else 
			return "";
	}
}