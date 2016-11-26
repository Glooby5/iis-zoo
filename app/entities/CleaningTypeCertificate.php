<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CleaningTypeCertificate extends Certificate
{
    /**
     * @var CleaningType
     * @ORM\ManyToOne(targetEntity="CleaningType")
     */
    protected $cleaningType;

    /**
     * @return CleaningType
     */
    public function getCleaningType()
    {
        return $this->cleaningType;
    }

    /**
     * @param CleaningType $enclosureType
     */
    public function setCleaningType(CleaningType $enclosureType)
    {
        $this->cleaningType = $enclosureType;
    }
}
