<?php

namespace App\Presenters;

use App\Entities\Feeding;
use App\Forms\FeedingFormFactory;
use App\Repositories\FeedingRepository;
use Nette;
use Nette\Http\IResponse;

class FeedingPresenter extends BasePresenter
{
    /** @var FeedingFormFactory @inject */
    public $feedingFormFactory;

    /** @var FeedingRepository @inject */
    public $feedingRepository;

    /** @var  Feeding|NULL */
    private $editingFeeding;

    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }

    public function renderCreate()
    {
        $this['feedingForm']['id']->setDisabled();
    }

    public function renderDetail($id)
    {
        $this->template->feeding = $this->feedingRepository->find($id);
    }

    public function renderDefault()
    {
        $this->template->feedings = $this->feedingRepository->findAll();
    }

    public function actionEdit($id)
    {
        $this->editingFeeding = $this->feedingRepository->find($id);

        if ( ! $this->editingFeeding) {
            $this->error('Neexistující uživatel');
        }
    }

    public function actionDelete($id)
    {
        $species = $this->feedingRepository->find($id);

        if ($species) {
            $this->feedingRepository->getEntityManager()->remove($species);
            $this->feedingRepository->getEntityManager()->flush();
            $this->flashMessage('Naplánované krmení bylo úspěšně odstraněno');
        }

        $this->redirect('Feeding:');
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentFeedingForm()
    {
        $form = $this->feedingFormFactory->create($this->editingFeeding);

        $form->onSuccess[] = function (Nette\Application\UI\Form $form, $values){
            $feeding = $this->feedingRepository->saveFormData($values);
            $this->redirect('Feeding:detail', $feeding->getId());
        };

        return $form;
    }
}
