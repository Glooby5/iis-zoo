<?php

namespace App\Forms;

use App\Entities\Feeding;
use App\Repositories\AnimalRepository;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;

class FeedingFormFactory extends Nette\Application\UI\Control
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
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(FormFactory $factory, AnimalRepository $animalRepository, UserRepository $userRepository)
    {
        $this->factory = $factory;
        $this->animalRepository = $animalRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param Feeding|NULL $feeding
     * @return Form
     */
    public function create(Feeding $feeding = NULL)
    {
        $form = new Form;

        $form->addHidden('id');

        $form->addSelect('keeper_id', 'Ošetřovatel')
            ->setItems($this->userRepository->findPairs())
            ->setPrompt('- vyberte -')
            ->setRequired();

        $form->addSelect('animal_id', 'Zvíře')
            ->setItems($this->animalRepository->findPairs())
            ->setPrompt('- vyberte -')
            ->setRequired();

        $form->addText('start', 'Začátek')
            ->setType('datetime')
            ->addRule(Form::FILLED, "`%label` je povinný.");

        $form->addText('end', 'Konec')
            ->setType('datetime')
            ->addRule(Form::FILLED, "`%label` je povinný.");

        $form->addText('species', 'Krmivo')
            ->addRule(Form::FILLED, "`%label` je povinný.");

        $form->addText('amount', 'Množství')
            ->addRule(Form::FILLED, "`%label` je povinné.");

        $form->addCheckbox('done', 'Provedeno');

        $form->addSubmit('save', 'Uložit');

        if ($feeding) {
            $this->setDefaults($feeding, $form);
        }

        return $form;
    }

    /**
     * @param Feeding $feeding
     * @param $form
     */
    private function setDefaults(Feeding $feeding, Form $form)
    {
        $form['id']->setDefaultValue($feeding->getId());
        $form['keeper_id']->setDefaultValue($feeding->getKeeper()->getId());
        $form['animal_id']->setDefaultValue($feeding->getAnimal()->getId());
        $form['species']->setDefaultValue($feeding->getSpecies());
        $form['amount']->setDefaultValue($feeding->getAmount());
        $form['done']->setDefaultValue($feeding->isDone());

        if ($feeding->getStart()) {
            $form['start']->setDefaultValue($feeding->getStart()->format('Y-m-d H:i:s'));
        }

        if ($feeding->getEnd()) {
            $form['end']->setDefaultValue($feeding->getEnd()->format('Y-m-d H:i:s'));
        }
    }
}
