<?php
namespace DataBundle\Manager;

use DataBundle\Entity\Episode;
use Symfony\Component\Config\Definition\Exception\Exception;
use DataBundle\Service\EpisodeService;
use DataBundle\Manager\CallRestManager;

class EpisodeManager {
    
    const SERIE_URL = 'https://api.thetvdb.com/series/';    

    /** @var CallRestManager $callRestManager **/
    private $callRestManager;
    
    /** @var EpisodeService $episodeService **/
    private $episodeService;

    public function __construct(
            CallRestManager $callRestManager,
            EpisodeService $episodeService
            ) 
    {
        $this->callRestManager = $callRestManager;
        $this->episodeService = $episodeService;
    }

    public function downloadEpisodes($idTvdbSerie) {
        $episodes = array();
        
        try {
            $infoEpisodes = $this->callRestManager->get($this::SERIE_URL.$idTvdbSerie.'/episodes');
        } catch (Exception $ex) {
            return $episodes;
        }        

        if( $infoEpisodes->data != null) {            
            foreach($infoEpisodes->data as $infoEpisode) {
                $episode = new Episode();
                $episode->populate($infoEpisode);
                $episodes[] = $episode;
            }
        }       

        return $episodes;
    }
    
    public function remove(Episode $episode) {
        $this->episodeService->remove($episode);
    }

}
