<?php

namespace App\Presenters;

use App\Repositories\UserRepository;
use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /** @var UserRepository @inject */
    public $userRepository;

    protected function startup()
    {
        if ($this->user->isLoggedIn()) {
            $user = $this->userRepository->find($this->user->getId());

            if ($this->user->getRoles() != $user->getRoles()) {
                $this->user->login($user);
            }
        }

        parent::startup();
    }


    public function printForbiddenMessage()
    {
        $this->flashMessage('K této akci nemáte oprávnění', 'danger');
    }

    public function flashMessage($message, $type = 'success')
    {
        return parent::flashMessage($message, $type);
    }
}
