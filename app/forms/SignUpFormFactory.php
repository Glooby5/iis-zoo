<?php

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;
use App\Model;


class SignUpFormFactory
{
	use Nette\SmartObject;

	const PASSWORD_MIN_LENGTH = 7;

	/**
     * @var FormFactory
     */
	private $factory;

    /**
     * @var Model\UserManager
     */
    private $userManager;

    public function __construct(FormFactory $factory, Model\UserManager $userManager)
	{
		$this->factory = $factory;
        $this->userManager = $userManager;
    }


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();
		$form->addText('firstname', 'Jméno:')
			->setRequired('Zadejte jméno.');

        $form->addText('lastname', 'Příjmení:')
			->setRequired('Zadejte příjmení.');

		$form->addText('email', 'E-maill:')
			->setRequired('Zadejte e-mail.')
			->addRule($form::EMAIL);

		$form->addPassword('password', 'Heslo:')
			->setOption('description', sprintf('at least %d characters', self::PASSWORD_MIN_LENGTH))
			->setRequired('Please create a password.')
			->addRule($form::MIN_LENGTH, NULL, self::PASSWORD_MIN_LENGTH);

        $form->addPassword('passwordCheck', 'Heslo znovu:')
            ->setRequired()
            ->addRule($form::EQUAL, 'Hesla se neshodují', $form['password']);

		$form->addSubmit('send', 'Zaregistrovat se');

		$form->onSuccess[] = [$this, 'onSuccess'];
		return $form;
	}


    public function onSuccess(Form $form)
    {
        try {
            $this->userManager->add($form->getValues());
        } catch (Model\DuplicateNameException $e) {
            $form->addError('Username is already taken.');
        }
	}

}
