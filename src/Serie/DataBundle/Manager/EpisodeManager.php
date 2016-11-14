<?php
namespace Serie\DataBundle\Manager;

use Serie\DataBundle\Entity\Episode;

class EpisodeManager {
    
    const SERIE_URL = 'https://api.thetvdb.com/series/';    

    private $callRestManager;

    public function __construct(CallRestManager $callRestManager) 
    {
        $this->callRestManager = $callRestManager;
    }

    public function downloadEpisodes($idTvdbSerie) {
        $infoEpisodes = $this->callRestManager->get($this::SERIE_URL.$idTvdbSerie.'/episodes');
        
        $episodes = array();
        foreach($infoEpisodes->data as $infoEpisode) {
            $episode = new Episode();
            $episode->populate($infoEpisode);
            $episodes[] = $episode;
        }

        return $episodes;
    }

}
