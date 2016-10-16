<?php

namespace App\Forms;

use App\Entities\EnclosureType;
use App\Repositories\EnclosureTypeRepository;
use Nette;
use Nette\Application\UI\Form;


class EnclosureTypeFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var EnclosureTypeRepository
     */
    private $enclosureTypeRepository;

    public function __construct(FormFactory $factory, EnclosureTypeRepository $enclosureTypeRepository)
    {
        $this->factory = $factory;
        $this->enclosureTypeRepository = $enclosureTypeRepository;
    }

    /**
     * @param EnclosureType|NULL $enclosureType
     * @return Form
     */
    public function create(EnclosureType $enclosureType = NULL)
    {
        $form = new Form;

        $form->addHidden('id')
        ;
        $form->addText('name', 'Název')
            ->addRule(Form::FILLED, "`%label` je povinný.")
        ;
        $form->addSubmit('save', 'Uložit');

        if ($enclosureType)
            $this->setDefaults($enclosureType, $form);

        return $form;
    }


    /**
     * @param EnclosureType $enclosureType
     * @param $form
     */
    private function setDefaults(EnclosureType $enclosureType, $form)
    {
        $form['id']->setDefaultValue($enclosureType->getId());
        $form['name']->setDefaultValue($enclosureType->getName());
    }
}
