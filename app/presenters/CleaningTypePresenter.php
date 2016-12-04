<?php

namespace App\Presenters;

use App\Entities\CleaningType;
use App\Forms\CleaningTypeFormFactory;
use App\Repositories\CleaningTypeRepository;
use Nette;
use Nette\Http\IResponse;


class CleaningTypePresenter extends SecuredPresenter
{
    /** @var CleaningTypeFormFactory @inject */
    public $cleaningTypeFormFactory;

    /** @var CleaningTypeRepository @inject */
    public $cleaningTypeRepository;

    /** @var  CleaningType|NULL */
    private $editingCleaningType;

    public function actionCreate()
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('CleaningType:');
        }
    }

    public function renderCreate()
    {
        $this['cleaningTypeForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->cleaningType = $this->cleaningTypeRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->cleaningTypes = $this->cleaningTypeRepository->findAll();
    }

    public function actionEdit($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('CleaningType:');
        }

        $this->editingCleaningType = $this->cleaningTypeRepository->find($id);

        if ( ! $this->editingCleaningType) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('CleaningType:');
        }

        $cleaningType = $this->cleaningTypeRepository->find($id);

        if ($cleaningType) {
            $this->cleaningTypeRepository->getEntityManager()->remove($cleaningType);
            $this->cleaningTypeRepository->getEntityManager()->flush();
            $this->flashMessage('Typ čištění "'. $cleaningType->getName() . ' byl úspěšně odstraněn');
        }

        $this->redirect('CleaningType:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentCleaningTypeForm()
    {
        $form = $this->cleaningTypeFormFactory->create($this->editingCleaningType);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $cleaningType = $this->cleaningTypeRepository->saveFormData($values);
            $this->redirect('CleaningType:detail', $cleaningType->getId());
        };

        return $form;
    }
}
