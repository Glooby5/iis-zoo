<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Animal enclosure
 * @ORM\Entity
 */
class Enclosure
{
    use Identifier;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $label;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $size;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    protected $capacity;

    /**
     * @var EnclosureType
     * @ORM\ManyToOne(targetEntity="EnclosureType", inversedBy="enclosures")
     */
    protected $enclosureType;

    /**
     * @var Animal[]
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="enclosure")
     */
    protected $animals;

    /**
     * Enclosure constructor.
     */
    public function __construct()
    {
        $this->animals = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $size
     */
    public function setSize(string $size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param int $capacity
     */
    public function setCapacity(int $capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return EnclosureType
     */
    public function getEnclosureType()
    {
        return $this->enclosureType;
    }

    /**
     * @param EnclosureType $enclosureType
     */
    public function setEnclosureType($enclosureType)
    {
        $this->enclosureType = $enclosureType;
    }

    /**
     * @return Animal[]
     */
    public function getAnimals()
    {
        return $this->animals;
    }
}
