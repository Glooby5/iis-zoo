<?php

namespace App\Presenters;

use App\Entities\User;
use App\Repositories\UserRepository;
use App\User\Forms\UserEditFormFactory;
use Nette;
use App\Forms;
use Nette\Http\IResponse;


class ProfilePresenter extends BasePresenter
{
    /** @var UserEditFormFactory @inject */
    public $userEditFormFactory;

    /** @var UserRepository @inject */
    public $userRepository;

    /** @var  User|null */
    public $editingUser;


    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }

    public function renderDefault()
    {
        $user = $this->userRepository->find($this->user->getId());

        if (!$user) {
            $this->printForbiddenMessage();
            $this->redirect('Homepage:');
        }

        $this->template->myUser = $user;
    }

    public function actionEdit()
    {
        $this->editingUser = $this->userRepository->find($this->user->getId());

        if (!$this->editingUser) {
            $this->printForbiddenMessage();
            $this->redirect('Homepage:');
        }
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentEditForm()
    {
        $form = $this->userEditFormFactory->create($this->editingUser);

        $form->onSuccess[] = function () {
            $this->flashMessage('Editace profilu proběhla úspěšně');
            $this->redirect('Profile:');
        };

        return $form;
    }
}
