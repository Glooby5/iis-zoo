<?php

namespace App\Forms;

use App\Entities\Certificate;
use App\Entities\EnclosureTypeCertificate;
use App\Entities\SpeciesCertificate;
use App\Repositories\EnclosureTypeRepository;
use App\Repositories\SpeciesRepository;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;


class EnclosureTypeCertificateFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;


    /**
     * @var CertificateFormFactory
     */
    private $certificateFormFactory;

    /**
     * @var EnclosureTypeRepository
     */
    private $enclosureTypeRepository;

    public function __construct(CertificateFormFactory $certificateFormFactory, EnclosureTypeRepository $enclosureTypeRepository)
    {
        $this->certificateFormFactory = $certificateFormFactory;
        $this->enclosureTypeRepository = $enclosureTypeRepository;
    }

    /**
     * @param EnclosureTypeCertificate|NULL $certificate
     * @return Form
     */
    public function create(EnclosureTypeCertificate $certificate = NULL)
    {
        $form = $this->certificateFormFactory->create($certificate);

        $form->addSelect('enclosure_type_id', 'Typ výběhu')
            ->setPrompt('- vyberte -')
            ->setItems($this->enclosureTypeRepository->findPairs())
            ->setRequired('Je nutné vyplnit typ výběhu');

        if ($certificate) {
            $this->setDefaults($certificate, $form);
        }

        return $form;
    }


    /**
     * @param Certificate $certificate
     * @param Form $form
     */
    private function setDefaults(EnclosureTypeCertificate $certificate, Form $form)
    {
        $form['enclosure_type_id']->setDefaultValue($certificate->getEnclosureType()->getId());
        $this->certificateFormFactory->setDefaults($certificate, $form);
    }
}
