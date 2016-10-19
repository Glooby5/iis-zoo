<?php

namespace App\Presenters;

use App\Entities\Cleaning;
use App\Forms\CleaningFormFactory;
use App\Repositories\CleaningRepository;
use Nette;
use Nette\Http\IResponse;

class CleaningPresenter extends BasePresenter
{
    /** @var CleaningFormFactory @inject */
    public $cleaningFormFactory;

    /** @var CleaningRepository @inject */
    public $cleaningRepository;

    /** @var Cleaning|NULL */
    private $editingCleaning;

    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }

    public function renderCreate()
    {
        $this['cleaningForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->cleaning = $this->cleaningRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->cleanings = $this->cleaningRepository->findAll();
    }

    public function actionEdit($id)
    {
        $this->editingCleaning = $this->cleaningRepository->find($id);

        if ( ! $this->editingCleaning) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        $species = $this->cleaningRepository->find($id);

        if ($species) {
            $this->cleaningRepository->getEntityManager()->remove($species);
            $this->cleaningRepository->getEntityManager()->flush();
            $this->flashMessage('Čištění bylo úspěšně odstraněno');
        }

        $this->redirect('Cleaning:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentCleaningForm()
    {
        $form = $this->cleaningFormFactory->create($this->editingCleaning);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $cleaning = $this->cleaningRepository->saveFormData($values);
            $this->redirect('Cleaning:detail', $cleaning->getId());
        };

        return $form;
    }
}
