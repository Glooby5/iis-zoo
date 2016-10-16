<?php

namespace App\Presenters;

use App\Entities\Species;
use App\Repositories\SpeciesRepository;
use App\User\Forms\SpeciesFormFactory;
use Nette;
use Nette\Http\IResponse;


class SpeciesPresenter extends BasePresenter
{
    /** @var SpeciesFormFactory @inject */
    public $speciesFormFactory;

    /** @var SpeciesRepository @inject */
    public $speciesRepository;

    /** @var  Species|NULL */
    private $editingSpecies;

    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }

    public function renderCreate()
    {
        $this['speciesForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->species = $this->speciesRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->allSpecies = $this->speciesRepository->findAll();
    }

    public function actionEdit($id)
    {
        $this->editingSpecies = $this->speciesRepository->find($id);

        if ( ! $this->editingSpecies) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        $species = $this->speciesRepository->find($id);

        if ($species) {
            $this->speciesRepository->getEntityManager()->remove($species);
            $this->speciesRepository->getEntityManager()->flush();
            $this->flashMessage('Druh '. $species->getName() . ' byl úspěšně odstraněn');
        }

        $this->redirect('Species:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentSpeciesForm()
    {
        $form = $this->speciesFormFactory->create($this->editingSpecies);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $species = $this->speciesRepository->saveFormData($values);
            $this->redirect('Species:detail', $species->getId());
        };

        return $form;
    }
}
