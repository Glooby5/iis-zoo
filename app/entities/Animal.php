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
    protected $from;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Species")
     */
    protected $species;

    /**
     * @var string
     * @ORM\ManyToOne(targetEntity="Enclosure")
     */
    protected $enclosure;
}
