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
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $start;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $end;

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
     * @ORM\ManyToMany(targetEntity="User", inversedBy="cleanings")
     */
    protected $cleaners;

    public function __construct()
    {
        $this->cleaners = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getAttendantsCount()
    {
        return $this->attendantsCount;
    }

    /**
     * @param int $attendantsCount
     */
    public function setAttendantsCount(int $attendantsCount)
    {
        $this->attendantsCount = $attendantsCount;
    }

    /**
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return Enclosure
     */
    public function getEnclosure()
    {
        return $this->enclosure;
    }

    /**
     * @param Enclosure $enclosure
     */
    public function setEnclosure(Enclosure $enclosure)
    {
        $this->enclosure = $enclosure;
    }

    /**
     * @return CleaningType
     */
    public function getCleaningType()
    {
        return $this->cleaningType;
    }

    /**
     * @param CleaningType $cleaningType
     */
    public function setCleaningType(CleaningType $cleaningType)
    {
        $this->cleaningType = $cleaningType;
    }

    /**
     * @return boolean
     */
    public function isDone()
    {
        return $this->done;
    }

    /**
     * @param boolean $done
     */
    public function setDone(bool $done)
    {
        $this->done = $done;
    }

    /**
     * @return ArrayCollection
     */
    public function getCleaners()
    {
        return $this->cleaners;
    }

    /**
     * @param User $cleaner
     */
    public function addCleaner(User $cleaner)
    {
        $this->cleaners->add($cleaner);
    }
}
