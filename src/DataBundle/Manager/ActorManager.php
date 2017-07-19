<?php
namespace DataBundle\Manager;

use DataBundle\Entity\Actor;
use Symfony\Component\Config\Definition\Exception\Exception;

class ActorManager {
    
    const SERIE_URL = 'https://api.thetvdb.com/series/';    

    private $callRestManager;

    public function __construct(CallRestManager $callRestManager) 
    {
        $this->callRestManager = $callRestManager;
    }

    public function downloadActors($idTvdbSerie) {
        $actors = array();
        
        try {
            $infoActors = $this->callRestManager->get($this::SERIE_URL.$idTvdbSerie.'/actors');
        } catch (Exception $ex) {
            return $actors;
        }          
        
        if ( $infoActors->data != null) {
            foreach($infoActors->data as $infoActor) {
                $actor = new Actor();
                $actor->populate($infoActor);
                $actors[] = $actor;
            }
        }       

        return $actors;
        
    }

}

