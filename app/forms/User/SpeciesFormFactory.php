<?php

namespace App\User\Forms;

use App\Entities\Species;
use App\Entities\User;
use App\Forms\FormFactory;
use App\Repositories\SpeciesRepository;
use Nette;
use Nette\Application\UI\Form;


class SpeciesFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var SpeciesRepository
     */
    private $speciesRepository;

    public $id;

    public function __construct(FormFactory $factory, SpeciesRepository $speciesRepository)
    {
        $this->factory = $factory;
        $this->speciesRepository = $speciesRepository;
    }

    /**
     * @param Species|NULL $species
     * @return Form
     */
    public function create(Species $species = NULL)
    {
        $form = new Form;

        $form->addHidden('id')
        ;
        $form->addText('name', 'Název')
            ->addRule(Form::FILLED, "`%label` je povinný.")
        ;
        $form->addText('occurrence', 'Výskyt')
        ;

        $form->addSubmit('save', 'Odeslat');

        if ($species)
            $this->setDefaults($species, $form);

        return $form;
    }


    /**
     * @param Species $species
     * @param $form
     */
    private function setDefaults(Species $species, $form)
    {
        $form['id']->setDefaultValue($species->getId());
        $form['name']->setDefaultValue($species->getName());
        $form['occurrence']->setDefaultValue($species->getOccurrence());
    }
}
