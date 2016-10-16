<?php

namespace App\Presenters;

use App\Entities\User;
use App\Repositories\UserRepository;
use App\User\Forms\UserEditFormFactory;
use Nette;
use App\Forms;
use Nette\Http\IResponse;


class UserPresenter extends BasePresenter
{
    /** @var UserEditFormFactory @inject */
    public $userEditFormFactory;

    /** @var UserRepository @inject */
    public $userRepository;

    /** @var  User */
    private $editingUser;


    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }


    public function actionEdit($id)
    {
        $this->editingUser = $this->userRepository->find($id);

        if ( ! $this->editingUser) {
            $this->error('Neexistující uživatel');
        }

        if ($this->user->getIdentity()->getId() != $id && ! $this->user->isInRole(User::ADMIN)) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }

        if ($this->user->getIdentity()->getId() != $id && $this->user->isInRole(User::ADMIN) && $this->editingUser->getRole() == User::ADMIN) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
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
            $this->flashMessage('Editace uživatele proběhla úspěšně');
            $this->redirect('User:detail', $this->editingUser->getId());
        };

        return $form;
    }


    public function renderDefault()
    {
        if ( ! $this->user->isInRole(User::ADMIN)) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }

        $this->template->users = $this->userRepository->findAll();
    }


    public function renderDetail($id)
    {
        $user = $this->userRepository->find($id);

        if ( ! $user) {
            $this->flashMessage('Neexistující uživatel', 'error');
            $this->redirect('User:');
        }

        $this->template->myUser = $user;
    }
}
