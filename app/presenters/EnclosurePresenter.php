<?php

namespace App\Presenters;

use App\Entities\Enclosure;
use App\Entities\User;
use App\Forms\EnclosureFormFactory;
use App\Repositories\EnclosureRepository;
use Nette;
use Nette\Http\IResponse;


class EnclosurePresenter extends SecuredPresenter
{
    /** @var EnclosureFormFactory @inject */
    public $speciesFormFactory;

    /** @var EnclosureRepository @inject */
    public $enclosureRepository;

    /** @var  Enclosure|NULL */
    private $editingEnclosure;

    public function actionCreate()
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('Enclosure:');
        }
    }

    public function renderCreate()
    {
        $this['enclosureForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->enclosure = $this->enclosureRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->enclosures = $this->enclosureRepository->findAll();
    }

    public function actionEdit($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('Enclosure:');
        }

        $this->editingEnclosure = $this->enclosureRepository->find($id);

        if ( ! $this->editingEnclosure) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        if ($this->user->isInRole(User::ATTENDANT)) {
            $this->printForbiddenMessage();
            $this->redirect('Enclosure:');
        }

        $species = $this->enclosureRepository->find($id);

        if ($species) {
            $this->enclosureRepository->getEntityManager()->remove($species);
            $this->enclosureRepository->getEntityManager()->flush();
            $this->flashMessage('Výběh "'. $species->getLabel() . '" byl úspěšně odstraněn');
        }

        $this->redirect('Enclosure:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentEnclosureForm()
    {
        $form = $this->speciesFormFactory->create($this->editingEnclosure);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $enclosure = $this->enclosureRepository->saveFormData($values);
            $this->redirect('Enclosure:detail', $enclosure->getId());
        };

        return $form;
    }
}
