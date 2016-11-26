<?php

namespace App\Forms;

use App\Entities\Certificate;
use App\Entities\SpeciesCertificate;
use App\Repositories\SpeciesRepository;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;


class SpeciesCertificateFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;


    /**
     * @var CertificateFormFactory
     */
    private $certificateFormFactory;

    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    public function __construct(CertificateFormFactory $certificateFormFactory, SpeciesRepository $speciesRepository)
    {
        $this->certificateFormFactory = $certificateFormFactory;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @param SpeciesCertificate|NULL $certificate
     * @return Form
     */
    public function create(SpeciesCertificate $certificate = NULL)
    {
        $form = $this->certificateFormFactory->create($certificate);

        $form->addSelect('species_id', 'Druh')
            ->setPrompt('- vyberte -')
            ->setItems($this->speciesRepository->findPairs())
            ->setRequired('Je nutné vyplnit druh');

        if ($certificate) {
            $this->setDefaults($certificate, $form);
        }

        return $form;
    }


    /**
     * @param Certificate $certificate
     * @param Form $form
     */
    private function setDefaults(SpeciesCertificate $certificate, Form $form)
    {
        $form['species_id']->setDefaultValue($certificate->getSpecies()->getId());
    }
}
