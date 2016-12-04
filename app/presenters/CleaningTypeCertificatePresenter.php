<?php

namespace App\Presenters;

use App\Entities\Certificate;
use App\Entities\Species;
use App\Entities\User;
use App\Forms\CleaningTypeCertificateFormFactory;
use App\Forms\SpeciesCertificateFormFactory;
use App\Repositories\CleaningTypeCertificateRepository;
use App\Repositories\SpeciesCertificateRepository;
use App\Repositories\SpeciesRepository;
use App\Forms\SpeciesFormFactory;
use Nette;
use Nette\Http\IResponse;


class CleaningTypeCertificatePresenter extends SecuredPresenter
{
    /** @var CleaningTypeCertificateFormFactory @inject */
    public $cleaningTypeCertificateFormFactory;

    /** @var CleaningTypeCertificateRepository @inject */
    public $cleaningTypeCertificateRepository;

    /** @var Certificate|NULL */
    private $editingCertificate;

    public function actionCreate()
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('CleaningTypeCertificate:');
        }
    }

    public function renderCreate()
    {
        $this['certificateForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->certificate = $this->cleaningTypeCertificateRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->allCertificates = $this->cleaningTypeCertificateRepository->findAll();
    }

    public function actionEdit($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('CleaningTypeCertificate:');
        }

        $this->editingCertificate = $this->cleaningTypeCertificateRepository->find($id);

        if ( ! $this->editingCertificate) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('CleaningTypeCertificate:');
        }

        $certificate = $this->cleaningTypeCertificateRepository->find($id);

        if ($certificate) {
            $this->cleaningTypeCertificateRepository->getEntityManager()->remove($certificate);
            $this->cleaningTypeCertificateRepository->getEntityManager()->flush();
            $this->flashMessage('Certifikát '. $certificate->getName() . ' byl úspěšně odstraněn');
        }

        $this->redirect('CleaningTypeCertificate:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentCertificateForm()
    {
        $form = $this->cleaningTypeCertificateFormFactory->create($this->editingCertificate);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $certificate = $this->cleaningTypeCertificateRepository->saveFormData($values);
            $this->redirect('CleaningTypeCertificate:detail', $certificate->getId());
        };

        return $form;
    }
}
