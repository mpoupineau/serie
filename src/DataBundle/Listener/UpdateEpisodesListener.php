<?php
namespace DataBundle\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use DataBundle\Event\UpdateEpisodesEvent;
use DataBundle\Entity\Serie;
use DataBundle\Manager\EpisodeManager;
use DataBundle\Service\SerieService;

class UpdateEpisodesListener //implements EventSubscriberInterface
{
    /** @var EpisodeManager */
    protected $episodeManager;

    /** @var SerieService */
    protected $serieService;

	

    public function __construct(
        EpisodeManager $episodeManager,
        SerieService $serieService
    )
    {
        $this->episodeManager = $episodeManager;
        $this->serieService = $serieService;
    }

    /*public static function getSubscribedEvents()
    {
        return array(
            UpdateEpisodesEvent::NAME => 'onUpdateEpisodes',
        );
    }*/

	public function onUpdateEpisodes(UpdateEpisodesEvent $event)
	{
        /** @var Serie $serie **/
		$serie = $event->getSerie();

		$yesterday = new \DateTime('yesterday');

		if($serie->getUpdatedAt() == null || $serie->getUpdatedAt() < $yesterday) {
			$episodes = $this->episodeManager->downloadEpisodes($serie->getIdDB());
            $episodesToDelete = $serie->getEpisodes();
            
            foreach ($episodesToDelete as $episodeToDelete) {
                $this->episodeManager->remove($episodeToDelete);
            }
            $serie->removeEpisodes();
            $this->serieService->save($serie);
			$serie->setEpisodes($episodes);
			$this->serieService->save($serie);
		}

	}
}
