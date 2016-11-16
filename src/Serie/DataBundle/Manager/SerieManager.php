<?php

namespace Serie\DataBundle\Manager;

use Serie\DataBundle\Entity\Serie;
use Serie\DataBundle\Service\SerieService;
use FM\FileManagementBundle\FileM\FileM;

class SerieManager {
    
    const SEARCH_SERIES_URL = 'https://api.thetvdb.com/search/series';
    const SERIE_URL = 'https://api.thetvdb.com/series/';  
    const IMAGE_URL = 'http://thetvdb.com/banners/';

    private $callRestManager;
    private $episodeManager;
    private $actorManager;
    private $fileManager;
    
    private $serieService;
    
    public function __construct(CallRestManager $callRestManager, 
                                EpisodeManager $episodeManager,
                                ActorManager $actorManager,
                                FileM $fileManager,
                                SerieService $serieService) 
    {
        $this->callRestManager = $callRestManager;
        $this->episodeManager = $episodeManager;
        $this->actorManager = $actorManager;
        $this->fileManager = $fileManager;
        $this->serieService = $serieService;
    }

    public function searchSeries($name) {
        $data = array(
            'name' => $name
        );
        $series = $this->callRestManager->get($this::SEARCH_SERIES_URL, 'EN', $data);

        return $series;
    }

    /**
     * Recherche et initialise en base les séries trouvées
     * 
     * @param type Nom de la série recherchée
     * @return Serie
     */
    public function initSeries($name) {
        $series = array();
        $infoSearchSeries = $this->searchSeries($name);
        if (!isset($infoSearchSeries->Error)) {
            
            if(count($infoSearchSeries->data) > 12) {
                return count($infoSearchSeries->data);
            }
            foreach ($infoSearchSeries->data as $infoSearchSerie) {
                //$repository = $this->entityManager->getRepository('SerieDataBundle:Serie');
                //$serie = $repository->findOneBy(array("idDB" => $infoSearchSerie->id));
                $serie = $this->serieService->findOneBy(array("idDB" => $infoSearchSerie->id));
                if($serie) {
                    $series[] = $serie;
                }
                else {
                    $serie = new Serie();
                    $serie->minPopulate($infoSearchSerie);
                    $serie->setPoster($this->downloadPoster($serie));

                    $this->serieService->save($serie);
                    $series[] = $serie;
                }
            }
        }
        
        return $series;
    }
    
    /**
     * Complete partiellement les données d'une serie
     * @param type $serie
     * @return type
     */
    public function partialComplete($serie) {
        $infoSerie = $this->callRestManager->get($this->getSerieUrl($serie->getIddb()));
        
        $serie->populate($infoSerie->data);
        if($infoSerie->data->banner != "") {
            $this->fileManager->uploadFile($this::IMAGE_URL.$infoSerie->data->banner, 'upload/banner/'.$infoSerie->data->banner);
        }
        $serie->setImportStatus(Serie::IMPORT_WAITING);
        
        $episodes = $this->episodeManager->downloadEpisodes($serie->getIddb());
        $actors = $this->actorManager->downloadActors($serie->getIddb());

        foreach ($episodes as $episode) {
            $serie->addEpisode($episode);
        }
        
        foreach ($actors as $actor) {
            $serie->addActor($actor);
        }
        
        $this->serieService->save($serie);
        
        return $serie;
    }
    
    public function downloadSerie($idTvdbSerie) {
        $infoSerie = $this->callRestManager->get($this->getSerieUrl($idTvdbSerie));
        
        $serie = new Serie();
        $serie->populate($infoSerie->data);
        $serie->setPoster($this->downloadPoster($serie));
        if($infoSerie->data->banner != "") {
            $this->fileManager->uploadFile($this::IMAGE_URL.$infoSerie->data->banner, 'upload/banner/'.$infoSerie->data->banner);
        }
        
        $episodes = $this->episodeManager->downloadEpisodes($idTvdbSerie);
        $actors = $this->actorManager->downloadActors($idTvdbSerie);

        foreach ($episodes as $episode) {
            $serie->addEpisode($episode);
        }
        
        foreach ($actors as $actor) {
            $serie->addActor($actor);
        }
        
        $this->serieService->save($serie);
        return 1;
    }
    
    /**
     * 
     * @param Serie $serie
     * @return pathFileImage
     */
    public function downloadPoster($serie) {
        $data = array(
            'keyType' => 'poster'
        );
        $infoPosters = $this->callRestManager->get($this->getPosterUrl($serie->getIdDB()), 'EN', $data);
        
        if (!isset($infoPosters->Error)) {
            $bestPosterFilename = "";
            $bestRate = 0;
            foreach ($infoPosters->data as $infoPoster) {
                if($bestRate < $infoPoster->ratingsInfo->average) {
                    $bestPosterFilename = $infoPoster->fileName;
                    $bestRate = $infoPoster->ratingsInfo->average;
                }
            }
            if($bestPosterFilename != "") {
                $this->fileManager->resizeFile($this::IMAGE_URL.$bestPosterFilename, 'upload/poster/'.$bestPosterFilename);
                return $bestPosterFilename;
            }
        }
        
		$this->create_image($serie->getName(), "upload/poster/default/".$serie->getSlug().".jpg");
        return "default/".$serie->getSlug().".jpg";
    }
    
    public function classifyByPopularity($series) {
        usort($series, array($this,'sortByPopularity'));
        return $series;
    }

    public function sortByPopularity($serieA, $serieB)
	{
		if($serieA->getRatingCount() > $serieB->getRatingCount()) {
            return -1;
        } 
        else {
            return 1;
        }
	}
    
    private function create_image($user, $pathImage){

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
    
    
    private function getSerieUrl($idTvdbSerie){
        return $this::SERIE_URL.$idTvdbSerie;
    }
    
    private function getPosterUrl($idTvdbSerie) {
        return $this::SERIE_URL.$idTvdbSerie.'/images/query';
    }
    
    public function find($id) {
        return $this->serieService->find($id);
    }
}