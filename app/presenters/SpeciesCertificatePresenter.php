<?php

namespace App\Presenters;

use App\Entities\Certificate;
use App\Entities\Species;
use App\Forms\SpeciesCertificateFormFactory;
use App\Repositories\SpeciesCertificateRepository;
use App\Repositories\SpeciesRepository;
use App\Forms\SpeciesFormFactory;
use Nette;
use Nette\Http\IResponse;


class SpeciesCertificatePresenter extends SecuredPresenter
{
    /** @var SpeciesCertificateFormFactory @inject */
    public $speciesCertificateFormFactory;

    /** @var SpeciesCertificateRepository @inject */
    public $speciesCertificateRepository;

    /** @var Certificate|NULL */
    private $editingCertificate;

    public function actionCreate()
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('SpeciesCertificate:');
        }
    }

    public function renderCreate()
    {
        $this['certificateForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->certificate = $this->speciesCertificateRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->allCertificates = $this->speciesCertificateRepository->findAll();
    }

    public function actionEdit($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('SpeciesCertificate:');
        }

        $this->editingCertificate = $this->speciesCertificateRepository->find($id);

        if ( ! $this->editingCertificate) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('SpeciesCertificate:');
        }

        $certificate = $this->speciesCertificateRepository->find($id);

        if ($certificate) {
            $this->speciesCertificateRepository->getEntityManager()->remove($certificate);
            $this->speciesCertificateRepository->getEntityManager()->flush();
            $this->flashMessage('Certifikát '. $certificate->getName() . ' byl úspěšně odstraněn');
        }

        $this->redirect('SpeciesCertificate:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentCertificateForm()
    {
        $form = $this->speciesCertificateFormFactory->create($this->editingCertificate);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $certificate = $this->speciesCertificateRepository->saveFormData($values);
            $this->redirect('SpeciesCertificate:detail', $certificate->getId());
        };

        return $form;
    }
}
