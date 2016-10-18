<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;

/**
 * @ORM\Entity
 */
class Feeding
{
    use Identifier;

    /**
     * \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $start;

    /**
     * \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $end;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $species;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $amount;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $done = false;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User")
     */
    protected $keeper;

    /**
     * @var Animal
     * @ORM\ManyToOne(targetEntity="Animal")
     */
    protected $animal;

    /**
     * @return \DateTime|NULL
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
     * @return \DateTime|NULL
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
     * @return string
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * @param string $species
     */
    public function setSpecies(string $species)
    {
        $this->species = $species;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param string $amount
     */
    public function setAmount(string $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return boolean
     */
    public function isDone(): bool
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
     * @return User
     */
    public function getKeeper()
    {
        return $this->keeper;
    }

    /**
     * @param User $keeper
     */
    public function setKeeper(User $keeper)
    {
        $this->keeper = $keeper;
    }

    /**
     * @return Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }

    /**
     * @param Animal $animal
     */
    public function setAnimal(Animal $animal)
    {
        $this->animal = $animal;
    }


}
