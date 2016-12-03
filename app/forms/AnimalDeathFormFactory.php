<?php

namespace App\Forms;

use App\Entities\Animal;
use Nette;
use Nette\Application\UI\Form;


class AnimalDeathFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @param Animal|NULL $animal
     * @return Form
     */
    public function create(Animal $animal = NULL)
    {
        $form = new Form;

        $form->addHidden('id')
        ;
        $form->addText('dateOfDeath', 'Datum a čas úmrtí')
            ->setType('datetime')
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
        $form['dateOfDeath']->setDefaultValue($animal->getDateOfDeath() ?  $animal->getDateOfDeath()->format('d-m-Y') : NULL);
    }
}
