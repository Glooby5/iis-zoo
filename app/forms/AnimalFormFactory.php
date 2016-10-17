<?php

namespace App\Forms;

use App\Entities\Animal;
use App\Repositories\AnimalRepository;
use App\Repositories\EnclosureRepository;
use App\Repositories\SpeciesRepository;
use Nette;
use Nette\Application\UI\Form;


class AnimalFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var AnimalRepository
     */
    private $animalRepository;

    /**
     * @var EnclosureRepository
     */
    private $enclosureRepository;

    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;


    public function __construct(
        FormFactory $factory,
        AnimalRepository $animalRepository,
        EnclosureRepository $enclosureRepository,
        SpeciesRepository $speciesRepository
    )
    {
        $this->factory = $factory;
        $this->animalRepository = $animalRepository;
        $this->enclosureRepository = $enclosureRepository;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @param Animal|NULL $animal
     * @return Form
     */
    public function create(Animal $animal = NULL)
    {
        $form = new Form;

        $form->addHidden('id');
        $form->addSelect('species_id', 'Druh')
            ->setPrompt('- vyberte -')
            ->setItems($this->speciesRepository->findPairs())
            ->setRequired()
        ;
        $form->addSelect('enclosure_id', 'Výběh')
            ->setPrompt('- vyberte -')
            ->setItems($this->enclosureRepository->findPairs())
            ->setRequired()
        ;
        $form->addText('name', 'Jméno')
            ->addRule(Form::FILLED, "`%label` je povinné.")
        ;
        $form->addSelect('sex', 'Pohlaví')
            ->setItems([Animal::MALE => 'samec', Animal::FEMALE => 'samice'])
            ->addRule(Form::FILLED, "`%label` je povinné.")
            ->setRequired()
        ;
        $form->addText('birthday', 'Datum narození')
            ->setType('datetime')
        ;
        $form->addText('country', 'Země původu')
        ;

        $form->addSubmit('save', 'Uložit');

        if ($animal)
            $this->setDefaults($animal, $form);

        return $form;
    }


    /**
     * @param Animal $animal
     * @param $form
     */
    private function setDefaults(Animal $animal, Form $form)
    {
        $form['id']->setDefaultValue($animal->getId());
        $form['species_id']->setDefaultValue($animal->getSpecies()->getId());
        $form['enclosure_id']->setDefaultValue($animal->getEnclosure()->getId());
        $form['name']->setDefaultValue($animal->getName());
        $form['sex']->setDefaultValue($animal->getSex());
        $form['birthday']->setDefaultValue($animal->getBirthday() ?  $animal->getBirthday()->format('Y-m-d') : NULL);
        $form['country']->setDefaultValue($animal->getCountry());
    }
}
