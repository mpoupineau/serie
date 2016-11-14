<?php
namespace Serie\DataBundle\Controller;

define ('KEY_DB', '837CC70044812B57');
define('TAG_SERIE', '//Data/Series/');
define('TAG_EPISODE', '//Data/Episode/');
define('TAG_ACTOR', '//Actors/Actor/');
define('TAG_MIRROR', '//Mirrors/Mirror/mirrorpath');
define('URL_SERIEINFO', '/api/GetSeries.php?seriesname=');
define('URL_PREFIX_ZIPFILE','/api/'.KEY_DB.'/series/');
define('URL_SUFFIX_ZIPFILE','/all/fr.zip');
define('URL_BANNER', '/banners/');
define('URL_POSTER', '/banners/_cache/');
define('URL_UPDATE_XMLFILE', '/api/'.KEY_DB.'/updates/updates_day.xml');
define('URL_SERIE', '/api/'.KEY_DB.'/series/');
define('URL_EPISODE', '/api/'.KEY_DB.'/episode/');
define('URL_MIRROR_XML', 'http://thetvdb.com/api/'.KEY_DB.'/mirrors.xml');
define('ZIPFILE', 'upload/serieData.zip');
define('XML_REPOSITORY', 'upload/xml/');
define('BANNER_REPOSITORY', 'upload/banner/');
define('POSTER_REPOSITORY', 'upload/poster/');

define ('NB_ELEMENT', '36');

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Serie\DataBundle\Entity\Serie;
use Serie\DataBundle\Entity\Episode;
use Serie\DataBundle\Entity\Actor;
use Serie\DataBundle\Form\SerieType;
use \DataBundle\Manager\SerieManager;

use GDText\Box;
use GDText\Color;

// controls the spacing between text

//JPG image quality 0-100


class SerieController extends Controller
{
/*	public function getSeriesFilterAction(Request $request) 
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		if ($request->isMethod('POST')) {
			
			$year_min = $this->getMinFiltre($request->get('years_range'));
			$year_max = $this->getMaxFiltre($request->get('years_range'));
			$rating_min = $this->getMinFiltre($request->get('rating_range'));
			$rating_max = $this->getMaxFiltre($request->get('rating_range'));
			$tab_category = $this->getTab_category($request->get('tab_category'));
			$status = $this->getContinuing($request->get('continuing'));
			$tab = "";
			foreach($tab_category as $category)
			{
				$tab .= $category;
			}
			// return new Response($tab);
			$list_Allseries = $repository->findByYearsCategoriesRatingStatus($year_min, $year_max, $tab_category, $rating_min, $rating_max, $status);
			$num_page = $request->get('num_page');
			$max_page = ceil(count($list_Allseries)/NB_ELEMENT);
			if($max_page < $request->get('num_page'))
				$num_page = $max_page;
			$list_series = $repository->findByYearsCategoriesRatingStatusPagination($year_min, $year_max, $tab_category, $rating_min, $rating_max, $status, $num_page, NB_ELEMENT);
			// return $this->render('SerieDataBundle:Serie:seriesFilter.html.twig',
				// array('list_series'  => $list_series));
			$response = new JsonResponse();
			$response->setData(array(
				'nb_series' => count($list_series),
				'html' => $this->renderView('SerieDataBundle:Serie:seriesFilter.html.twig',array('list_series'  => $list_series, 'page' => $num_page,'max_page' => $max_page))
			));
			return $response;
		}
	
		
		$list_Allseries = $repository->findAll();
		/*$em = $this->getDoctrine()->getManager();
		foreach($list_Allseries as $serie) {
			if($serie->getPoster() == "")
			{
				$file_name = $this->create_image($serie->getName(), "upload/poster/default/".$serie->getSlug().".jpg");
				$serie->setPoster("default/".$serie->getSlug().".jpg");
				$em->persist($serie);
				$em->flush();
			}
		}*/
		//$response = $this->get('serieManager')->searchSeries('westworld');
		/*$response = "1";//$this->get('serieManager')->downloadSerie(75760);
		$list_series = $repository->findAllPagination(1, 36);
		return $this->render('SerieDataBundle:Serie:seriesFilter.html.twig',
			array('list_series'  => $list_series,
					'page' => 1,
                    'response' => $response,
					'max_page' => ceil(count($list_Allseries)/36)));
					// return "";
	}
	*/
	/*public function getSerieAction(Request $request)
	{
		if ($request->isMethod('POST')) {
			$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
				$series = $this->getSeriesFromKeyword($request->get('serie_name'));
				$response = new JsonResponse();
				
				if(count($series) == 0) 			// Aucune série trouvée
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
	}*/
	
	public function autocompleteAction()
	{
		$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
		$series = $repository->findAll();
		$fileM = $this->get('fm_file_management.filem');
		$script = $this->renderView('SerieDataBundle:Serie:autocomplete.html.twig',
			array('series'  => $series));
		file_put_contents('js/autocomplete.js', $script);
		return new Response("autocomplete_data();");
	}

	public function formAction()
	{
		$serie = new Serie();
		$form = $this->createForm(SerieType::class, $serie);
		return $this->render('SerieAppBundle:Serie:form.html.twig', array(
		'form' => $form->createView(), ));
	}
	/********************************* Méthode ***********************************/
	public function getTab_category($tab_category)
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
	public function getContinuing($continuing)
	{
		if($continuing == "oui")
			return "continuing";
		else if($continuing == "non")
			return "ended";
		else 
			return "";
	}
	public function getMinFiltre($data_range)
	{
		return substr($data_range,0, strpos($data_range," "));
	}
	
	public function getMaxFiltre($data_range)
	{
		$strTemp = substr($data_range,3);
		return substr($strTemp, strpos($data_range," "));
	}
	// new 
	public function getSeriesFromKeyword($keyword)
	{
		$seriesToReturn = array();
		$serie = urlencode($keyword);
		$fileM = $this->get('fm_file_management.filem');
		$mirror = $this->getMirror();
		$em = $this->getDoctrine()->getManager();
		$crawler_infoSerie = new Crawler($fileM->getContentsFromFile($mirror.URL_SERIEINFO.'\"'.$serie.'\"'));
		
		$list_seriesID 	= $crawler_infoSerie->filterXpath(TAG_SERIE.'seriesid')->extract('_text');
		foreach($list_seriesID as $serieID)
		{
			$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
			$serie = $repository->findOneByIdDB($serieID);
			if($serie) 	{				// si la série existe, on ajoute la série
				$seriesToReturn[] = $serie;
			}
			else {
				if (!$em->isOpen()) {
					$em = $em->create(
						$em->getConnection(),
						$em->getConfiguration()
					);
				}
				try {
					$crawler_serie = new Crawler($fileM->getContentsFromFile($mirror.URL_PREFIX_ZIPFILE.$serieID.'/fr.xml'));
					$serie = new Serie("empty", "");
					$serie->setName($crawler_serie->filterXpath(TAG_SERIE.'SeriesName')->text());
					$serie->setIdDB($crawler_serie->filterXpath(TAG_SERIE.'id')->text());
					$serie->setPoster($crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
					$em->persist($serie);
					$em->flush();
					if($crawler_serie->filterXpath(TAG_SERIE.'poster')->text() != "") {}
						//$fileM->uploadFile($mirror.URL_POSTER.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text(), POSTER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
					else {
						//$file_name = $this->create_image($serie->getName(), "upload/poster/default/".$serie->getSlug().".jpg");
						$serie->setPoster("default/".$serie->getSlug().".jpg");
						$em->persist($serie);
						$em->flush();
					}
					
					$seriesToReturn[] = $serie;	
				}
				catch(\Exception $e){
					error_log($e->getMessage());
				}
			}
		}
		return $seriesToReturn;
	}
	
	public function updateDataSerie($serie_idDB)
	{
		$em = $this->getDoctrine()->getManager();
		$repository = $em->getRepository('SerieDataBundle:Serie');
		$serie = $repository->findOneByIdDB($serie_idDB);
		$fileM = $this->get('fm_file_management.filem');
		$mirror = $this->getMirror();
				
		$fileM->uploadFile($mirror.URL_PREFIX_ZIPFILE.$serie_idDB.URL_SUFFIX_ZIPFILE, ZIPFILE);	
		$fileM->extractZip(ZIPFILE, XML_REPOSITORY);
		$crawler_serie = new Crawler($fileM->getContentsFromFile(XML_REPOSITORY.'fr.xml'));
		
		$serie->updateData($crawler_serie);
		if($crawler_serie->filterXpath(TAG_SERIE.'banner')->text() != "") 
			$fileM->uploadFile($mirror.URL_BANNER.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text(), BANNER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text());
		if((stripos($serie->getPoster(), 'default/') !== false) AND ($crawler_serie->filterXpath(TAG_SERIE.'poster')->text() !== ''))
			$fileM->uploadFile($mirror.URL_POSTER.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text(), POSTER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text());

		foreach($serie->getEpisodes() as $episode)
		{
			$em->remove($episode);
		}
		foreach($serie->getActors() as $actor)
		{
			$em->remove($actor);
		}
		$em->flush();
		// **************** List of episode's data  **************** //
		$list_seasonNumber 	= $crawler_serie->filterXpath(TAG_EPISODE.'SeasonNumber')->extract('_text');
		$list_episodeNumber = $crawler_serie->filterXpath(TAG_EPISODE.'EpisodeNumber')->extract('_text');
		$list_episodeName 	= $crawler_serie->filterXpath(TAG_EPISODE.'EpisodeName')->extract('_text');
		$list_language 		= $crawler_serie->filterXpath(TAG_EPISODE.'Language')->extract('_text');
		$list_overview 		= $crawler_serie->filterXpath(TAG_EPISODE.'Overview')->extract('_text');
		$list_rating 		= $crawler_serie->filterXpath(TAG_EPISODE.'Rating')->extract('_text');
		$list_episodeImage 	= $crawler_serie->filterXpath(TAG_EPISODE.'filename')->extract('_text');
		$list_director 		= $crawler_serie->filterXpath(TAG_EPISODE.'Director')->extract('_text');
		$list_writer 		= $crawler_serie->filterXpath(TAG_EPISODE.'Writer')->extract('_text');
		$list_lastUpdated 	= $crawler_serie->filterXpath(TAG_EPISODE.'lastupdated')->extract('_text');
		$list_firstAired 	= $crawler_serie->filterXpath(TAG_EPISODE.'FirstAired')->extract('_text');
		$list_idDB		 	= $crawler_serie->filterXpath(TAG_EPISODE.'id')->extract('_text');
		
		
		for($i = 0; $i < count($list_seasonNumber) ;$i++)
		{
			$episode = new Episode();
			$episode->setSeasonNumber($list_seasonNumber[$i]);
			$episode->setEpisodeNumber($list_episodeNumber[$i]);
			$episode->setEpisodeName($list_episodeName[$i]);
			$episode->setLanguage($list_language[$i]);
			$episode->setOverview($list_overview[$i]);
			$episode->setRating($list_rating[$i]);
			$episode->setEpisodeImage($list_episodeImage[$i]);
			$episode->setDirector($list_director[$i]);
			$episode->setWriter($list_writer[$i]);
			$episode->setLastUpdated($list_lastUpdated[$i]);
			$episode->setFirstAired(new \DateTime($list_firstAired[$i]));
			$episode->setIdDB($list_idDB[$i]);
			$serie->addEpisode($episode);
			$em->persist($episode);
		}
		
		$crawler_actors = new Crawler($fileM->getContentsFromFile(XML_REPOSITORY.'actors.xml'));
				
		// **************** List of episode's data  **************** //
		$list_idDB 		= $crawler_actors->filterXpath(TAG_ACTOR.'id')->extract('_text');
		$list_name 		= $crawler_actors->filterXpath(TAG_ACTOR.'Name')->extract('_text');
		$list_role 		= $crawler_actors->filterXpath(TAG_ACTOR.'Role')->extract('_text');
		$list_sortOrder	= $crawler_actors->filterXpath(TAG_ACTOR.'SortOrder')->extract('_text');
		
		for($i = 0; $i < count($list_idDB) ;$i++)
		{
			$actor = new Actor();
			$actor->setIdDB($list_idDB[$i]);
			$actor->setName($list_name[$i]);
			$actor->setRole($list_role[$i]);
			$actor->setSortOrder($list_sortOrder[$i]);
			$serie->addActor($actor);
			$em->persist($actor);
		}
		$em->persist($serie);
		$em->flush();
		
		return $serie;
	}
	
	// old
	public function getSeriesFromKeywor($keyword)
	{
		$seriesToReturn = array();
		$serie = urlencode($keyword);
		$fileM = $this->get('fm_file_management.filem');
		$mirror = $this->getMirror();
		$em = $this->getDoctrine()->getManager();
		$crawler_infoSerie = new Crawler($fileM->getContentsFromFile($mirror.URL_SERIEINFO.'\"'.$serie.'\"'));
		
		$list_seriesID 	= $crawler_infoSerie->filterXpath(TAG_SERIE.'seriesid')->extract('_text');
		foreach($list_seriesID as $serieID)
		{
			$repository = $this->getDoctrine()->getManager()->getRepository('SerieDataBundle:Serie');
			$serie = $repository->findOneByIdDB($serieID);
			if($serie) 	{				// si la série existe, on ajoute la série
				$seriesToReturn[] = $serie;
			}
			else {
				if (!$em->isOpen()) {
					$em = $em->create(
						$em->getConnection(),
						$em->getConfiguration()
					);
				}
				try {
				$fileM->uploadFile($mirror.URL_PREFIX_ZIPFILE.$serieID.URL_SUFFIX_ZIPFILE, ZIPFILE);	
				$fileM->extractZip(ZIPFILE, XML_REPOSITORY);
				$crawler_serie = new Crawler($fileM->getContentsFromFile(XML_REPOSITORY.'fr.xml'));
				
				$serie = new Serie("byCrawler", $crawler_serie);
				
				if($crawler_serie->filterXpath(TAG_SERIE.'banner')->text() != "") 
					$fileM->uploadFile($mirror.URL_BANNER.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text(), BANNER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text());
				if($crawler_serie->filterXpath(TAG_SERIE.'poster')->text() != "")
					$fileM->uploadFile($mirror.URL_POSTER.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text(), POSTER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
				else {
					$file_name = $this->create_image($serie->getName(), "upload/poster/default/".$serie->getSlug().".jpg");
					$serie->setPoster("default/".$serie->getSlug().".jpg");
				}
				// **************** List of episode's data  **************** //
				$list_seasonNumber 	= $crawler_serie->filterXpath(TAG_EPISODE.'SeasonNumber')->extract('_text');
				$list_episodeNumber = $crawler_serie->filterXpath(TAG_EPISODE.'EpisodeNumber')->extract('_text');
				$list_episodeName 	= $crawler_serie->filterXpath(TAG_EPISODE.'EpisodeName')->extract('_text');
				$list_language 		= $crawler_serie->filterXpath(TAG_EPISODE.'Language')->extract('_text');
				$list_overview 		= $crawler_serie->filterXpath(TAG_EPISODE.'Overview')->extract('_text');
				$list_rating 		= $crawler_serie->filterXpath(TAG_EPISODE.'Rating')->extract('_text');
				$list_episodeImage 	= $crawler_serie->filterXpath(TAG_EPISODE.'filename')->extract('_text');
				$list_director 		= $crawler_serie->filterXpath(TAG_EPISODE.'Director')->extract('_text');
				$list_writer 		= $crawler_serie->filterXpath(TAG_EPISODE.'Writer')->extract('_text');
				$list_lastUpdated 	= $crawler_serie->filterXpath(TAG_EPISODE.'lastupdated')->extract('_text');
				$list_firstAired 	= $crawler_serie->filterXpath(TAG_EPISODE.'FirstAired')->extract('_text');
				$list_idDB		 	= $crawler_serie->filterXpath(TAG_EPISODE.'id')->extract('_text');
				
				for($i = 0; $i < count($list_seasonNumber) ;$i++)
				{
					$episode = new Episode();
					$episode->setSeasonNumber($list_seasonNumber[$i]);
					$episode->setEpisodeNumber($list_episodeNumber[$i]);
					$episode->setEpisodeName($list_episodeName[$i]);
					$episode->setLanguage($list_language[$i]);
					$episode->setOverview($list_overview[$i]);
					$episode->setRating($list_rating[$i]);
					$episode->setEpisodeImage($list_episodeImage[$i]);
					$episode->setDirector($list_director[$i]);
					$episode->setWriter($list_writer[$i]);
					$episode->setLastUpdated($list_lastUpdated[$i]);
					$episode->setFirstAired(new \DateTime($list_firstAired[$i]));
					$episode->setIdDB($list_idDB[$i]);
					//$episode->setSerie($serie);
					$serie->addEpisode($episode);
					$em->persist($episode);
				}
				
				$crawler_actors = new Crawler($fileM->getContentsFromFile(XML_REPOSITORY.'actors.xml'));
				
				// **************** List of episode's data  **************** //
				$list_idDB 		= $crawler_actors->filterXpath(TAG_ACTOR.'id')->extract('_text');
				$list_name 		= $crawler_actors->filterXpath(TAG_ACTOR.'Name')->extract('_text');
				$list_role 		= $crawler_actors->filterXpath(TAG_ACTOR.'Role')->extract('_text');
				$list_sortOrder	= $crawler_actors->filterXpath(TAG_ACTOR.'SortOrder')->extract('_text');
				
				for($i = 0; $i < count($list_idDB) ;$i++)
				{
					$actor = new Actor();
					$actor->setIdDB($list_idDB[$i]);
					$actor->setName($list_name[$i]);
					$actor->setRole($list_role[$i]);
					$actor->setSortOrder($list_sortOrder[$i]);
					$serie->addActor($actor);
					$em->persist($actor);
				}
				$em->persist($serie);
				$em->flush();
				$seriesToReturn[] = $serie;	
				}
				catch(\Exception $e){
					error_log($e->getMessage());
				}
			}
		}
		return $seriesToReturn;
	}
	
	public function getMirror()
	{
		$fileM = $this->get('fm_file_management.filem');
		
		$crawler_mirror = new Crawler($fileM->getContentsFromFile(URL_MIRROR_XML));
		return $crawler_mirror->filterXpath(TAG_MIRROR)->text();
	}
	
	
	public function create_image($user, $pathImage){

		$fontname = 'fonts/Capriola-Regular.ttf';
		$quality = 90;
		$i=30;
		$height=20;
		$font_size = 27;
		$file = $pathImage;	
	
	// if the file already exists dont create it again just serve up the original	
	//if (!file_exists($file)) {
		$words = explode(" ", $user);		
		$title = array();
		$temp = "";
		foreach ($words as $word){
			if(strlen($temp) == 0) {
				$temp = $word;
			}
			else if(strlen($temp)+strlen($word) <= 12)
			{
				$temp .= " ".$word;
			}
			else {
				$title[] = $temp;
				$temp = $word;
			}
		}
		$title[] = $temp;
		// $title[0] = $words[0];
			// define the base image that we lay our text on
			$im = imagecreatefromjpeg("cover/default_poster.jpg");
			
			// setup the text colours
			$color['grey'] = imagecolorallocate($im, 54, 56, 60);
			$color['green'] = imagecolorallocate($im, 55, 189, 102);
			$color['blue'] = imagecolorallocate($im, 255, 255, 255);
			
			// this defines the starting height for the text block
			$y = imagesy($im) - $height - 265;
			 
		// loop through the array and write the text	
		foreach ($title as $value){
			// center the text in our image - returns the x value
			$x = $this->center_text($value, $font_size);	
			imagettftext($im, $font_size, 0, $x, $y+$i, $color['blue'], $fontname,$value);
			// add 32px to the line height for the next text block
			$i = $i+52;	
			
		}
			// create the image
			imagejpeg($im, $file, $quality);
			
	//}
						
		return $file;	
}

	public function center_text($string, $font_size){

				$fontname = 'fonts/Capriola-Regular.ttf';

				$image_width = 300;
				$dimensions = imagettfbbox($font_size, 0, $fontname, $string);
				
				return ceil(($image_width - $dimensions[4]) / 2);				
	}

}