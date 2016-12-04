<?php

namespace App\Presenters;

use App\Entities\EnclosureType;
use App\Entities\User;
use App\Forms\EnclosureTypeFormFactory;
use App\Repositories\EnclosureTypeRepository;
use Nette;
use Nette\Http\IResponse;


class EnclosureTypePresenter extends SecuredPresenter
{
    /** @var EnclosureTypeFormFactory @inject */
    public $speciesFormFactory;

    /** @var EnclosureTypeRepository @inject */
    public $enclosureTypeRepository;

    /** @var  EnclosureType|NULL */
    private $editingEnclosureType;

    public function actionCreate()
    {
        if (!$this->user->isInRole(User::ADMIN)) {
            $this->printForbiddenMessage();
            $this->redirect('EnclosureType:');
        }
    }

    public function renderCreate()
    {
        $this['enclosureTypeForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->enclosureType = $this->enclosureTypeRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->enclosureTypes = $this->enclosureTypeRepository->findAll();
    }

    public function actionEdit($id)
    {
        if (!$this->user->isInRole(User::ADMIN)) {
            $this->printForbiddenMessage();
            $this->redirect('EnclosureType:');
        }

        $this->editingEnclosureType = $this->enclosureTypeRepository->find($id);

        if ( ! $this->editingEnclosureType) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        if (!$this->user->isInRole(User::ADMIN)) {
            $this->printForbiddenMessage();
            $this->redirect('EnclosureType:');
        }

        $species = $this->enclosureTypeRepository->find($id);

        if ($species) {
            $this->enclosureTypeRepository->getEntityManager()->remove($species);
            $this->enclosureTypeRepository->getEntityManager()->flush();
            $this->flashMessage('Typ výběhu "'. $species->getName() . '"" byl úspěšně odstraněn');
        }

        $this->redirect('EnclosureType:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentEnclosureTypeForm()
    {
        $form = $this->speciesFormFactory->create($this->editingEnclosureType);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $species = $this->enclosureTypeRepository->saveFormData($values);
            $this->redirect('EnclosureType:detail', $species->getId());
        };

        return $form;
    }
}
