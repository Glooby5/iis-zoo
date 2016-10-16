<?php

namespace App\Presenters;

use App\Entities\EnclosureType;
use App\Forms\EnclosureTypeFormFactory;
use App\Repositories\EnclosureTypeRepository;
use Nette;
use Nette\Http\IResponse;


class EnclosureTypePresenter extends BasePresenter
{
    /** @var EnclosureTypeFormFactory @inject */
    public $speciesFormFactory;

    /** @var EnclosureTypeRepository @inject */
    public $enclosureTypeRepository;

    /** @var  EnclosureType|NULL */
    private $editingEnclosureType;

    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
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
        $this->editingEnclosureType = $this->enclosureTypeRepository->find($id);

        if ( ! $this->editingEnclosureType) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
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
