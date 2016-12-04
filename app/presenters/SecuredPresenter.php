<?php

namespace App\Presenters;

use App\Entities\User;
use App\Repositories\UserRepository;
use Nette;
use App\Model;
use Nette\Http\IResponse;


/**
 * Base presenter for all application presenters.
 */
abstract class SecuredPresenter extends BasePresenter
{
    protected function startup()
    {
        parent::startup();

        if ( ! $this->user->isLoggedIn() || $this->user->isInRole(User::REGISTERED)) {
            $this->error('Nemáte oprávnění', IResponse::S403_FORBIDDEN);
        }
    }
}
