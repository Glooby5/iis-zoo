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


    public function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn()) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }

    /**
     * Edit form factory.
     * @return Nette\Application\UI\Form
     */
    protected function createComponentEditForm()
    {
        $form = $this->userEditFormFactory->create($this->user->getIdentity());

        $form->onSuccess[] = function () {
            $this->flashMessage('Editace profilu proběhla úspěšně');
            $this->redirect('Profile:');
        };

        return $form;
    }
}
