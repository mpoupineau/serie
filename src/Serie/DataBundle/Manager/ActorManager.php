<?php
namespace Serie\DataBundle\Manager;

use Serie\DataBundle\Entity\Actor;

class ActorManager {
    
    const SERIE_URL = 'https://api.thetvdb.com/series/';    

    private $callRestManager;

    public function __construct(CallRestManager $callRestManager) 
    {
        $this->callRestManager = $callRestManager;
    }

    public function downloadActors($idTvdbSerie) {
        $infoActors = $this->callRestManager->get($this::SERIE_URL.$idTvdbSerie.'/actors');
        
        $actors = array();
        foreach($infoActors->data as $infoActor) {
            $actor = new Actor();
            $actor->populate($infoActor);
            $actors[] = $actor;
        }

        return $actors;
        
    }

}

