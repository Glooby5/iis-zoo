<?php

namespace App\Forms;

use App\Entities\Certificate;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;


class CertificateFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param Certificate|NULL $certificate
     * @return Form
     */
    public function create(Certificate $certificate = NULL)
    {
        $form = new Form;

        $form->addHidden('id');
        $form->addText('name', 'Název')->setDefaultValue('Certifikát na práci s druhem');

        $form->addText('start', 'Začátek platnosti')
            ->setType('datetime')
            ->addRule(Form::FILLED, "`%label` je povinný.");

        $form->addText('end', 'Konec platnosti')
            ->setType('datetime')
            ->addRule(Form::FILLED, "`%label` povinný.");

        $form->addSelect('user_id', 'Uživatel', $this->userRepository->findPairs())
            ->addRule(Form::FILLED, "`%label` povinný.");


        $form->addSubmit('save', 'Uložit');

        if ($certificate) {
            $this->setDefaults($certificate, $form);
        }

        return $form;
    }


    /**
     * @param Certificate $certificate
     * @param Form $form
     */
    private function setDefaults(Certificate $certificate, Form $form)
    {
        $form['id']->setDefaultValue($certificate->getId());
        $form['user_id']->setDefaultValue($certificate->getUser()->getId());

        if ($certificate->getStart()) {
            $form['start']->setDefaultValue($certificate->getStart()->format('Y-m-d H:i:s'));
        }

        if ($certificate->getEnd()) {
            $form['end']->setDefaultValue($certificate->getEnd()->format('Y-m-d H:i:s'));
        }
    }
}
