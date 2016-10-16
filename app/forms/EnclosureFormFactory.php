<?php

namespace App\Forms;

use App\Entities\Enclosure;
use App\Repositories\EnclosureRepository;
use App\Repositories\EnclosureTypeRepository;
use Nette;
use Nette\Application\UI\Form;


class EnclosureFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var EnclosureRepository
     */
    private $enclosureRepository;

    /**
     * @var EnclosureTypeRepository
     */
    private $enclosureTypeRepository;

    public function __construct(FormFactory $factory, EnclosureRepository $enclosureRepository, EnclosureTypeRepository $enclosureTypeRepository)
    {
        $this->factory = $factory;
        $this->enclosureRepository = $enclosureRepository;
        $this->enclosureTypeRepository = $enclosureTypeRepository;
    }

    /**
     * @param Enclosure|NULL $enclosure
     * @return Form
     */
    public function create(Enclosure $enclosure = NULL)
    {
        $form = new Form;

        $form->addHidden('id')
        ;
        $form->addSelect('enclosureType', 'Typ výběhu')
            ->setPrompt('- vyberte -')
            ->setItems($this->enclosureTypeRepository->findPairs())
        ;
        $form->addText('label', 'Název')
            ->addRule(Form::FILLED, "`%label` je povinný.")
        ;
        $form->addText('size', 'Velikost')
            ->addRule(Form::FILLED, "`%label` je povinná.")
        ;
        $form->addText('capacity', 'Kapacita')
            ->setType('number')
            ->addRule(Form::FILLED, "`%label` je povinná.")
            ->addRule(Form::INTEGER, "`%label` musí být číslo.")
        ;

        $form->addSubmit('save', 'Uložit');

        if ($enclosure)
            $this->setDefaults($enclosure, $form);

        return $form;
    }


    /**
     * @param Enclosure $enclosure
     * @param $form
     */
    private function setDefaults(Enclosure $enclosure, Form $form)
    {
        $form['id']->setDefaultValue($enclosure->getId());
        $form['label']->setDefaultValue($enclosure->getLabel());
        $form['size']->setDefaultValue($enclosure->getSize());
        $form['capacity']->setDefaultValue($enclosure->getCapacity());
        $form['enclosureType']->setDefaultValue($enclosure->getEnclosureType()->getId());
    }
}
