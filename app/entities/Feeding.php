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
    protected $time;

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
}
