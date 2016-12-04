<?php

namespace App\Forms;

use App\Entities\Feeding;
use App\Repositories\AnimalRepository;
use App\Repositories\SpeciesCertificateRepository;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use Symfony\Component\Console\Tests\Fixtures\DescriptorApplication1;

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

    /**
     * @var SpeciesCertificateRepository
     */
    private $speciesCertificateRepository;

    public function __construct(FormFactory $factory, AnimalRepository $animalRepository, UserRepository $userRepository, SpeciesCertificateRepository $speciesCertificateRepository)
    {
        $this->factory = $factory;
        $this->animalRepository = $animalRepository;
        $this->userRepository = $userRepository;
        $this->speciesCertificateRepository = $speciesCertificateRepository;
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
            ->setRequired('Vyberte prosím ošetřovatele');

        $form->addSelect('animal_id', 'Zvíře')
            ->setItems($this->animalRepository->findPairs())
            ->setPrompt('- vyberte -')
            ->setRequired('Vyberte prosím zvíře');

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

        $form->onValidate[] = [$this, 'validateCertificates'];

        return $form;
    }

    public function validateCertificates(Form $form, $values)
    {
        $startDate = new DateTime($values->start);
        $endDate = new DateTime($values->end);

        if ($endDate <= $startDate) {
            $form->addError("Konec akce nemůže být menší nebo stejný jako začátek");
        }


        $animal = $this->animalRepository->find($values->animal_id);
        $certificates = $this->speciesCertificateRepository->canUserFeedSpecies($values->keeper_id, $animal->getSpecies()->getId(), $startDate);

        if (!$certificates) {
            $form->addError("Ošetřovatel nemá platný certifikát umožňující krmení tohoto druhu");
            return false;
        }

        $freeTime = $this->userRepository->hasUserFreeTime($values->keeper_id, $startDate, $endDate, $values->id, NULL);

        if (!$freeTime) {
            $form->addError("Ošetřovatel má v zadanou dobu naplánované jiné aktivity");
            return false;
        }

        return true;
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
            $form['start']->setDefaultValue($feeding->getStart()->format('d-m-Y H:i:s'));
        }

        if ($feeding->getEnd()) {
            $form['end']->setDefaultValue($feeding->getEnd()->format('d-m-Y H:i:s'));
        }
    }
}
