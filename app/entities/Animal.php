<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\Entity
 */
class Animal
{
    use Identifier;

    const MALE = 'male';
    const FEMALE = 'female';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $sex;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $birthday;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $country;

    /**
     * @var Species
     * @ORM\ManyToOne(targetEntity="Species", inversedBy="animals")
     */
    protected $species;

    /**
     * @var Enclosure
     * @ORM\ManyToOne(targetEntity="Enclosure", inversedBy="animals")
     */
    protected $enclosure;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $dateOfDeath;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $dead = FALSE;

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
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     */
    public function setSex(string $sex)
    {
        $this->sex = $sex;
    }

    /**
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday(\DateTime $birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }

    /**
     * @return Species
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param Species $species
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;
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
     * @return \DateTime
     */
    public function getDateOfDeath()
    {
        return $this->dateOfDeath;
    }

    /**
     * @param \DateTime $dateOfDeath
     */
    public function setDateOfDeath(\DateTime $dateOfDeath)
    {
        $this->dateOfDeath = $dateOfDeath;
    }

    /**
     * @return boolean
     */
    public function isDead(): bool
    {
        return $this->dead;
    }

    /**
     * @param boolean $dead
     */
    public function setDead(bool $dead)
    {
        $this->dead = $dead;
    }


}
