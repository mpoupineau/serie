<?php
namespace DataBundle\Controller;

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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DomCrawler\Crawler;
use DataBundle\Entity\Serie;
use DataBundle\Entity\Episode;
use DataBundle\Entity\Actor;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class LoadDataController extends Controller
{
	public function updateSeriesAction()
    {
		$fileM = $this->get('fm_file_management.filem'); 
		$mirror = getMirror();
		$em = $this->getDoctrine()->getManager();
		$crawler_updateFile = new Crawler($fileM->getContentsFromFile($mirror.URL_UPDATE_XMLFILE));
		$list_updateSeries 	= $crawler_updateFile->filterXpath(TAG_SERIE.'id')->extract('_text');
				
		$repositorySerie = $this->getDoctrine()->getManager()->getRepository('DataBundle:Serie');
		foreach($list_updateSeries as $updateSerie)
		{
			$serie = false;
			$serie = $repositorySerie->findOneByIdDB($updateSerie);
			if($serie)
			{
				$crawler_updateSerie = new Crawler($fileM->getContentsFromFile($mirror.URL_SERIE.$updateSerie.'/fr.xml'));
				
				$serie->setName($crawler_updateSerie->filterXpath(TAG_SERIE.'SeriesName')->text());
				$serie->setActors($crawler_updateSerie->filterXpath(TAG_SERIE.'Actors')->text());
				$serie->setAirsDayOfWeek($crawler_updateSerie->filterXpath(TAG_SERIE.'Airs_DayOfWeek')->text());
				$serie->setFirstAired(new \DateTime($crawler_updateSerie->filterXpath(TAG_SERIE.'FirstAired')->text()));
				$serie->setGenre($crawler_updateSerie->filterXpath(TAG_SERIE.'Genre')->text());
				$serie->setOverview($crawler_updateSerie->filterXpath(TAG_SERIE.'Overview')->text());
				$serie->setRating($crawler_updateSerie->filterXpath(TAG_SERIE.'Rating')->text());
				$serie->setRuntime($crawler_updateSerie->filterXpath(TAG_SERIE.'Runtime')->text());
				$serie->setStatus($crawler_updateSerie->filterXpath(TAG_SERIE.'Status')->text());
			}			
		}
		$em->flush();
		
		$list_updateEpisodes = $crawler_updateFile->filterXpath(TAG_EPISODE.'id')->extract('_text');
		$repositoryEpisode = $this->getDoctrine()->getManager()->getRepository('DataBundle:Serie');
		foreach($list_updateEpisodes as $updateEpisode)
		{
			$episode = false;
			$episode = $repositoryEpisode->findOneByIdDB($updateEpisode);
			if($episode)
			{
				$crawler_updateEpisode = new Crawler($fileM->getContentsFromFile($mirror.URL_EPISODE.$updateEpisode.'/fr.xml'));
				
				$episode->setSeasonNumber($crawler_updateEpisode->filterXpath(TAG_EPISODE.'SeasonNumber')->text());
				$episode->setEpisodeNumber($crawler_updateEpisode->filterXpath(TAG_EPISODE.'EpisodeNumber')->text());
				$episode->setEpisodeName($crawler_updateEpisode->filterXpath(TAG_EPISODE.'EpisodeName')->text());
				$episode->setOverview($crawler_updateEpisode->filterXpath(TAG_EPISODE.'Overview')->text());
				$episode->setRating($crawler_updateEpisode->filterXpath(TAG_EPISODE.'Rating')->text());
				$episode->setDirector($crawler_updateEpisode->filterXpath(TAG_EPISODE.'Director')->text());
				$episode->setWriter($crawler_updateEpisode->filterXpath(TAG_EPISODE.'Writer')->text());
				$episode->setFirstAired(new \DateTime($crawler_updateEpisode->filterXpath(TAG_EPISODE.'FirstAired')->text()));
			}			
		}
		$em->flush();
		$error = "seems cool";
		return $this->render('AppBundle:Default:index.html.twig',
			array('contents'  => $error));
	}

	
    public function addSeriesAction(Request $request)
    {
		$data = array();
		$form = $this->createFormBuilder($data)
			->add('serie_name', TextType::class, array('label' => 'Nom de la série'))
			->add('submit', SubmitType::class, array('label' => 'Rechercher'))
			->getForm();
		
		if ($request->isMethod('POST')) {
			$serie = urlencode($request->get('serie_name'));
			$errors[] = 'Aucune série "'.$request->get('serie_name').'" n\'a été trouvée';
			$slug[] = "none";
			$fileM = $this->get('fm_file_management.filem');
			$mirror = $this->getMirror();
			$em = $this->getDoctrine()->getManager();
			// $serie = 'under%20the%20dome'; // %20 game%20of%20thrones the%20simpsons how%20i%20met%20your%20mother american%20dad under%20the%20dome
			
			$crawler_infoSerie = new Crawler($fileM->getContentsFromFile($mirror.URL_SERIEINFO.'\"'.$serie.'\"'));
			
			$list_seriesID 	= $crawler_infoSerie->filterXpath(TAG_SERIE.'seriesid')->extract('_text');
			foreach($list_seriesID as $serieID)
			{
				try {
				
				    if (!$em->isOpen()) {
						$em = $em->create(
							$em->getConnection(),
							$em->getConfiguration()
						);
					}
				$fileM->uploadFile($mirror.URL_PREFIX_ZIPFILE.$serieID.URL_SUFFIX_ZIPFILE, ZIPFILE);	
				$fileM->extractZip(ZIPFILE, XML_REPOSITORY);
				$crawler_serie = new Crawler($fileM->getContentsFromFile(XML_REPOSITORY.'fr.xml'));
				
				$serie = new Serie($crawler_serie);
				
				if($crawler_serie->filterXpath(TAG_SERIE.'banner')->text() != "")
					$fileM->uploadFile($mirror.URL_BANNER.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text(), BANNER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text());
				if($crawler_serie->filterXpath(TAG_SERIE.'poster')->text() != "")
					$fileM->uploadFile($mirror.URL_POSTER.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text(), POSTER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
				
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
				$tab_serie[] = $serie;
				}
				catch(\Exception $e){
					error_log($e->getMessage());
					// $errors[] = "Echec de l'insertion dans la base de données : ".$e->getMessage();
					$errors[] = $serie->getName();
					$slug[] = substr($serie->getSlug(), 0, strlen($serie->getSlug())-2);
				}
			}
			if(isset($tab_serie))
				return $this->render('DataBundle:LoadData:displayNewSerie.html.twig', array(
					'list_series'  => $tab_serie,
					'errors' => $errors,
					'slug' => $slug
					));
			else
				return $this->render('DataBundle:LoadData:errorNewSerie.html.twig', array(
					'errors' => $errors,
					'slug' => $slug
					));
			
		}
		return $this->render('DataBundle:LoadData:addSerie.html.twig', array(
            'form' => $form->createView(),
        ));
    }
	 public function addSeriessAction()
    {
		$error = "Pas de problem";
		$fileM = $this->get('fm_file_management.filem');
		$mirror = $this->getMirror();
		$em = $this->getDoctrine()->getManager();
		$serie = 'under%20the%20dome'; // %20 game%20of%20thrones the%20simpsons how%20i%20met%20your%20mother american%20dad under%20the%20dome
		
		$crawler_infoSerie = new Crawler($fileM->getContentsFromFile($mirror.URL_SERIEINFO.'\"'.$serie.'\"'));
		
		$list_seriesID 	= $crawler_infoSerie->filterXpath(TAG_SERIE.'seriesid')->extract('_text');
		foreach($list_seriesID as $serieID)
		{
			try {
			$fileM->uploadFile($mirror.URL_PREFIX_ZIPFILE.$serieID.URL_SUFFIX_ZIPFILE, ZIPFILE);	
			$fileM->extractZip(ZIPFILE, XML_REPOSITORY);
			$crawler_serie = new Crawler($fileM->getContentsFromFile(XML_REPOSITORY.'fr.xml'));
			
			$serie = new Serie($crawler_serie);
			
			if($crawler_serie->filterXpath(TAG_SERIE.'banner')->text() != "")
				$fileM->uploadFile($mirror.URL_BANNER.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text(), BANNER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'banner')->text());
			if($crawler_serie->filterXpath(TAG_SERIE.'poster')->text() != "")
				$fileM->uploadFile($mirror.URL_POSTER.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text(), POSTER_REPOSITORY.$crawler_serie->filterXpath(TAG_SERIE.'poster')->text());
			
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
			}
			catch(\Exception $e){
				error_log($e->getMessage());
				$error = "Echec de l'insertion dans la base de donn�es : ".$e->getMessage();
			}
		}
		
        return $this->render('AppBundle:Default:index.html.twig',
			array('contents'  => $error));
    }
	public function getMirror()
	{
		$fileM = $this->get('fm_file_management.filem');
		
		$crawler_mirror = new Crawler($fileM->getContentsFromFile(URL_MIRROR_XML));
		return $crawler_mirror->filterXpath(TAG_MIRROR)->text();
	}
}