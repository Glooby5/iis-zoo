<?php

namespace App\Forms;

use App\Entities\CleaningType;
use Nette;
use Nette\Application\UI\Form;

class CleaningTypeFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @param CleaningType|NULL $cleaningType
     * @return Form
     */
    public function create(CleaningType $cleaningType = NULL)
    {
        $form = new Form;

        $form->addHidden('id');

        $form->addText('name', 'Název')
            ->addRule(Form::FILLED, "`%label` je povinný.");

        $form->addText('tools', 'Pomůcky')
            ->addRule(Form::FILLED, "`%label` jsou povinné.");

        $form->addSubmit('save', 'Uložit');

        if ($cleaningType) {
            $this->setDefaults($cleaningType, $form);
        }

        return $form;
    }

    /**
     * @param CleaningType $cleaningType
     * @param $form
     */
    private function setDefaults(CleaningType $cleaningType, Form $form)
    {
        $form['id']->setDefaultValue($cleaningType->getId());
        $form['name']->setDefaultValue($cleaningType->getName());
        $form['tools']->setDefaultValue($cleaningType->getTools());
    }
}
