<?php

namespace App\Forms;

use App\Entities\Cleaning;
use App\Entities\User;
use App\Repositories\CleaningTypeCertificateRepository;
use App\Repositories\CleaningTypeRepository;
use App\Repositories\EnclosureRepository;
use App\Repositories\EnclosureTypeCertificateRepository;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;


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

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CleaningTypeCertificateRepository
     */
    private $cleaningTypeCertificateRepository;

    /**
     * @var EnclosureTypeCertificateRepository
     */
    private $enclosureTypeCertificateRepository;

    public function __construct
    (
        FormFactory $factory,
        CleaningTypeRepository $cleaningTypeRepository,
        EnclosureRepository $enclosureRepository,
        UserRepository $userRepository,
        CleaningTypeCertificateRepository $cleaningTypeCertificateRepository,
        EnclosureTypeCertificateRepository $enclosureTypeCertificateRepository
    )
    {
        $this->factory = $factory;
        $this->cleaningTypeRepository = $cleaningTypeRepository;
        $this->enclosureRepository = $enclosureRepository;
        $this->userRepository = $userRepository;
        $this->cleaningTypeCertificateRepository = $cleaningTypeCertificateRepository;
        $this->enclosureTypeCertificateRepository = $enclosureTypeCertificateRepository;
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
            ->setRequired('Vyberte prosím výběh pro čištění');

        $form->addSelect('cleaning_type_id', 'Typ čištění')
            ->setItems($this->cleaningTypeRepository->findPairs())
            ->setPrompt('- vyberte -')
            ->setRequired('Vyberte prosím typ čištění');

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

        $form->addMultiSelect('cleaners', 'Ošetřovatelé', $this->userRepository->findPairs());

        $form->addCheckbox('done', 'Provedeno');

        $form->addSubmit('save', 'Uložit');

        if ($cleaning) {
            $this->setDefaults($cleaning, $form);
        }

        $form->onValidate[] = [$this, 'validateCertificates'];

        return $form;
    }

    public function validateCertificates(Form $form, $values)
    {
        $enclosure = $this->enclosureRepository->find($values->enclosure_id);

        foreach ($values->cleaners as $cleaner) {
            $certificates = $this->cleaningTypeCertificateRepository->canUserDoCleaningType($cleaner, $values->cleaning_type_id, new DateTime($values->start));

            if (!$certificates) {
                $form->addError("Ošetřovatel nemá platný certifikát umožňující tento typ čištění");
                return false;
            }

            $certificates = $this->enclosureTypeCertificateRepository->canUserEnclosureType($cleaner, $enclosure->getEnclosureType()->getId(), new DateTime($values->start));

            if (!$certificates) {
                $form->addError("Ošetřovatel nemá platný certifikát umožňující čistit zadaný typ výběhu");
                return false;
            }

            $freeTime = $this->userRepository->hasUserFreeTime($cleaner, new DateTime($values->start), new DateTime($values->end), null, $values->id);

            if (!$freeTime) {
                $form->addError("Ošetřovatel má v zadanou dobu naplánované jiné aktivity");
                return false;
            }
        }

        return true;
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
            $form['start']->setDefaultValue($cleaning->getStart()->format('d-m-Y H:i:s'));
        }

        if ($cleaning->getEnd()) {
            $form['end']->setDefaultValue($cleaning->getEnd()->format('d-m-Y H:i:s'));
        }

        if ($cleaning->getEnclosure()) {
            $form['enclosure_id']->setDefaultValue($cleaning->getEnclosure()->getId());
        }

        if ($cleaning->getCleaningType()) {
            $form['cleaning_type_id']->setDefaultValue($cleaning->getCleaningType()->getId());
        }

        $cleanersIds = $cleaning->getCleaners()->map(function (User $user) {
            return $user->getId();
        })->toArray();

        $form['cleaners']->setDefaultValue($cleanersIds);
    }
}
