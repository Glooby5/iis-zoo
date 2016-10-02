<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Utils\DateTime;

/**
 * @ORM\Entity
 */
class User
{
    use Identifier;

    const CLEANER = 'cleaner';
    const ATTENDANT = 'attendant';
    const ADMIN = 'admin';

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true))
     */
    protected $personalNumber;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
     protected $birthday;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true))
     */
    protected $title;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $role;

    /**
     * @var Cleaning[]
     * @ORM\ManyToMany(targetEntity="Cleaning", inversedBy="cleaners")
     */
    protected $cleanings;
}
