<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\Entity
 */
class Cleaning
{
    use Identifier;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $attendantsCount;

    /**
     * @var \DateInterval
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $timeNeeded;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $time;

    /**
     * @var Enclosure
     * @ORM\ManyToOne(targetEntity="Enclosure")
     */
    protected $enclosure;

    /**
     * @var CleaningType
     * @ORM\ManyToOne(targetEntity="CleaningType")
     */
    protected $cleaningType;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $done = false;

    /**
     * @var User[]
     * @ORM\ManyToMany(targetEntity="User", mappedBy="cleanings")
     */
    protected $cleaners;

    public function __construct()
    {
        $this->cleaners = new ArrayCollection();
    }
}
