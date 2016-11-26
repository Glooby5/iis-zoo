<?php

namespace App\Presenters;

use App\Entities\Certificate;
use App\Entities\Species;
use App\Forms\EnclosureTypeCertificateFormFactory;
use App\Forms\SpeciesCertificateFormFactory;
use App\Repositories\EnclosureTypeCertificateRepository;
use App\Repositories\SpeciesCertificateRepository;
use App\Repositories\SpeciesRepository;
use App\Forms\SpeciesFormFactory;
use Nette;
use Nette\Http\IResponse;


class EnclosureTypeCertificatePresenter extends BasePresenter
{
    /** @var EnclosureTypeCertificateFormFactory @inject */
    public $speciesCertificateFormFactory;

    /** @var EnclosureTypeCertificateRepository @inject */
    public $enclosureTypeCertificateRepository;

    /** @var Certificate|NULL */
    private $editingCertificate;

    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }

    public function renderCreate()
    {
        $this['certificateForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->certificate = $this->enclosureTypeCertificateRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->allCertificates = $this->enclosureTypeCertificateRepository->findAll();
    }

    public function actionEdit($id)
    {
        $this->editingCertificate = $this->enclosureTypeCertificateRepository->find($id);

        if ( ! $this->editingCertificate) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        $certificate = $this->enclosureTypeCertificateRepository->find($id);

        if ($certificate) {
            $this->enclosureTypeCertificateRepository->getEntityManager()->remove($certificate);
            $this->enclosureTypeCertificateRepository->getEntityManager()->flush();
            $this->flashMessage('Certifikát '. $certificate->getName() . ' byl úspěšně odstraněn');
        }

        $this->redirect('EnclosureTypeCertificate:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentCertificateForm()
    {
        $form = $this->speciesCertificateFormFactory->create($this->editingCertificate);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $certificate = $this->enclosureTypeCertificateRepository->saveFormData($values);
            $this->redirect('EnclosureTypeCertificate:detail', $certificate->getId());
        };

        return $form;
    }
}
