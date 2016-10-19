<?php

namespace App\Forms;

use App\Entities\Cleaning;
use App\Repositories\CleaningTypeRepository;
use App\Repositories\EnclosureRepository;
use Nette;
use Nette\Application\UI\Form;


class CleaningFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var CleaningTypeRepository
     */
    private $cleaningTypeRepository;

    /**
     * @var EnclosureRepository
     */
    private $enclosureRepository;

    public function __construct
    (
        FormFactory $factory,
        CleaningTypeRepository $cleaningTypeRepository,
        EnclosureRepository $enclosureRepository
    )
    {
        $this->factory = $factory;
        $this->cleaningTypeRepository = $cleaningTypeRepository;
        $this->enclosureRepository = $enclosureRepository;
    }

    /**
     * @param Cleaning|NULL $cleaning
     * @return Form
     */
    public function create(Cleaning $cleaning = NULL)
    {
        $form = new Form;

        $form->addHidden('id');

        $form->addSelect('enclosure_id', 'Výběh')
            ->setItems($this->enclosureRepository->findPairs())
            ->setPrompt('- vyberte -')
            ->setRequired();

        $form->addSelect('cleaning_type_id', 'Typ čištění')
            ->setItems($this->cleaningTypeRepository->findPairs())
            ->setPrompt('- vyberte -')
            ->setRequired();

        $form->addText('attendants_count', 'Počet ošetřovatelů')
            ->setType('number')
            ->addRule(Form::FILLED, "`%label` je povinný.")
            ->addRule(Form::INTEGER, "`%label` musí být číslo.");

        $form->addText('start', 'Začátek')
            ->setType('datetime')
            ->addRule(Form::FILLED, "`%label` je povinný.");

        $form->addText('end', 'Konec')
            ->setType('datetime')
            ->addRule(Form::FILLED, "`%label` povinný.");

        $form->addCheckbox('done', 'Provedeno');

        $form->addSubmit('save', 'Uložit');

        if ($cleaning) {
            $this->setDefaults($cleaning, $form);
        }

        return $form;
    }


    /**
     * @param Cleaning $cleaning
     * @param $form
     */
    private function setDefaults(Cleaning $cleaning, Form $form)
    {
        $form['id']->setDefaultValue($cleaning->getId());
        $form['attendants_count']->setDefaultValue($cleaning->getAttendantsCount());
        $form['done']->setDefaultValue($cleaning->isDone());

        if ($cleaning->getStart()) {
            $form['start']->setDefaultValue($cleaning->getStart()->format('Y-m-d H:i:s'));
        }

        if ($cleaning->getEnd()) {
            $form['end']->setDefaultValue($cleaning->getEnd()->format('Y-m-d H:i:s'));
        }

        if ($cleaning->getEnclosure()) {
            $form['enclosure_id']->setDefaultValue($cleaning->getEnclosure()->getId());
        }

        if ($cleaning->getCleaningType()) {
            $form['cleaning_type_id']->setDefaultValue($cleaning->getCleaningType()->getId());
        }
    }
}
