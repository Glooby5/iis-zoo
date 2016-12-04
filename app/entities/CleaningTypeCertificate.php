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
     * @ORM\JoinColumn(onDelete="CASCADE")
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
     * @param CleaningType $cleaningType
     */
    public function setCleaningType(CleaningType $cleaningType)
    {
        $this->cleaningType = $cleaningType;
    }
}
