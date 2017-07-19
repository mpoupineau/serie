<?php
namespace DataBundle\Event;

use DataBundle\Entity\Serie;
use Symfony\Component\EventDispatcher\Event;


class UpdateEpisodesEvent extends Event
{
    const NAME = 'episodes.update';

    /**
     * @var SerieEntity
     */
    protected $serie;

    public function __construct(Serie $serie)
    {
        $this->serie = $serie;
    }

    public function getSerie()
    {
        return $this->serie;
    }
}

