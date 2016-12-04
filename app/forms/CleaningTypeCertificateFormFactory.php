<?php

namespace App\Forms;

use App\Entities\Certificate;
use App\Entities\CleaningTypeCertificate;
use App\Entities\EnclosureTypeCertificate;
use App\Entities\SpeciesCertificate;
use App\Repositories\CleaningTypeRepository;
use App\Repositories\EnclosureTypeRepository;
use App\Repositories\SpeciesRepository;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;


class CleaningTypeCertificateFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;


    /**
     * @var CertificateFormFactory
     */
    private $certificateFormFactory;

    /**
     * @var CleaningTypeRepository
     */
    private $enclosureTypeRepository;

    public function __construct(CertificateFormFactory $certificateFormFactory, CleaningTypeRepository $enclosureTypeRepository)
    {
        $this->certificateFormFactory = $certificateFormFactory;
        $this->enclosureTypeRepository = $enclosureTypeRepository;
    }

    /**
     * @param CleaningTypeCertificate|NULL $certificate
     * @return Form
     */
    public function create(CleaningTypeCertificate $certificate = NULL)
    {
        $form = $this->certificateFormFactory->create($certificate);

        $form->addSelect('cleaning_type_id', 'Typ čištění')
            ->setPrompt('- vyberte -')
            ->setItems($this->enclosureTypeRepository->findPairs())
            ->setRequired('Je nutné vyplnit typ čištění');

        if ($certificate) {
            $this->setDefaults($certificate, $form);
        }

        return $form;
    }


    /**
     * @param Certificate $certificate
     * @param Form $form
     */
    private function setDefaults(CleaningTypeCertificate $certificate, Form $form)
    {
        $form['cleaning_type_id']->setDefaultValue($certificate->getCleaningType()->getId());
        $this->certificateFormFactory->setDefaults($certificate, $form);
    }
}
