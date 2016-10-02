<?php

namespace App\Entities;

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
     * @ORM\ManyToOne(targetEntity="EnclosureType")
     */
    protected $enclosureType;
}
