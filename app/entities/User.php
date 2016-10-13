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

    const REGISTERED = 'registered';
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

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return string
     */
    public function getPersonalNumber(): string
    {
        return $this->personalNumber;
    }

    /**
     * @param string $personalNumber
     */
    public function setPersonalNumber(string $personalNumber)
    {
        $this->personalNumber = $personalNumber;
    }

    /**
     * @return DateTime
     */
    public function getBirthday(): DateTime
    {
        return $this->birthday;
    }

    /**
     * @param DateTime $birthday
     */
    public function setBirthday(DateTime $birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role)
    {
        $this->role = $role;
    }

    /**
     * @return Cleaning[]
     */
    public function getCleanings(): array
    {
        return $this->cleanings;
    }

    /**
     * @param Cleaning[] $cleanings
     */
    public function setCleanings(array $cleanings)
    {
        $this->cleanings = $cleanings;
    }
}
