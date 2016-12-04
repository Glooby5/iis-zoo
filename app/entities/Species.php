<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * Animal species
 * @ORM\Entity
 */
class Species
{
    use Identifier;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $occurrence;

    /**
     * @var Animal[]
     * @ORM\OneToMany(targetEntity="Animal", mappedBy="species", cascade={"remove"})
     */
    protected $animals;

    /**
     * Species constructor.
     */
    public function __construct()
    {
        $this->animals = new ArrayCollection();
    }


    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOccurrence()
    {
        return $this->occurrence;
    }

    /**
     * @param string $occurrence
     */
    public function setOccurrence(string $occurrence)
    {
        $this->occurrence = $occurrence;
    }

    /**
     * @return Animal[]
     */
    public function getAnimals()
    {
        return $this->animals;
    }
}
