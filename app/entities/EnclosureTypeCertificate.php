<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class EnclosureTypeCertificate extends Certificate
{
    /**
     * @var EnclosureType
     * @ORM\ManyToOne(targetEntity="EnclosureType")
     */
    protected $enclosureType;

    /**
     * @return EnclosureType
     */
    public function getEnclosureType()
    {
        return $this->enclosureType;
    }

    /**
     * @param EnclosureType $enclosureType
     */
    public function setEnclosureType(EnclosureType $enclosureType)
    {
        $this->enclosureType = $enclosureType;
    }
}
