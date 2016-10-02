<?php

namespace App\Entities;

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
}
