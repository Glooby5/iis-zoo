<?php

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\Entity
 */
class EnclosureType
{
    use Identifier;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var Enclosure[]
     * @ORM\OneToMany(targetEntity="Enclosure", mappedBy="enclosureType")
     */
    protected $enclosures;


    /**
     * EnclosureType constructor.
     */
    public function __construct()
    {
        $this->enclosures = new ArrayCollection();
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
     * @return Enclosure[]
     */
    public function getEnclosures()
    {
        return $this->enclosures;
    }

    /**
     * @param Enclosure[] $enclosures
     */
    public function setEnclosures(array $enclosures)
    {
        $this->enclosures = $enclosures;
    }
}
