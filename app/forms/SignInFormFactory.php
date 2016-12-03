<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User;


class SignInFormFactory
{
	use Nette\SmartObject;

	/** @var FormFactory */
	private $factory;

	/** @var User */
	private $user;


	public function __construct(FormFactory $factory, User $user)
	{
		$this->factory = $factory;
		$this->user = $user;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();
		$form->addText('username', 'Username:')
            ->setRequired('Zadejte prosím e-mail.');

		$form->addPassword('password', 'Password:')
            ->setRequired('Zadejte heslo k přihlášení.');

		$form->addCheckbox('remember', 'Zapamatovat');

		$form->addSubmit('send', 'Přihlásit se');

		$form->onSuccess[] = [$this, 'onSuccess'];

		return $form;
	}

    public function onSuccess(Form $form, $values)
    {
        try {
            $this->user->setExpiration($values->remember ? '14 days' : '20 minutes');
            $this->user->login($values->username, $values->password);
        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Chybný uživatelský e-mail nebo heslo.');
        }
    }
}
