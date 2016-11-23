<?php

namespace App\User\Forms;

use App\Entities\User;
use App\Forms\FormFactory;
use App\Repositories\UserRepository;
use Nette;
use Nette\Application\UI\Form;


class UserEditFormFactory extends Nette\Application\UI\Control
{
    use Nette\SmartObject;

    const PASSWORD_MIN_LENGTH = 7;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var Nette\Security\Uusser
     */
    private $user;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(FormFactory $factory, Nette\Security\User $user, UserRepository $userRepository)
    {
        $this->factory = $factory;
        $this->user = $user;
        $this->userRepository = $userRepository;
    }


    /**
     * @param User $user
     * @return Form
     */
    public function create(User $user)
    {
        $form = $this->factory->create();

        $form->addHidden('id')
        ;
        $form->addText('email', 'E-mail')
            ->setType('email')
            ->addRule(Form::FILLED, "`%label` je povinný.")
            ->addRule(Form::EMAIL, '`%label` musí mít správný formát.')
        ;
        $form->addText('firstname', 'Jméno')
            ->addRule(Form::FILLED, "`%label` je poviné.")
        ;
        $form->addText('lastname', 'Příjmení')
            ->addRule(Form::FILLED, "`%label` je povinné.")
        ;
        $form->addText('personalNumber', 'Rodné číslo')
        ;
        $form->addText('birthday', 'Datum narození')
            ->setType('datetime')
        ;
        $form->addText('title', 'Titul')
        ;
        $form->addSelect('role', 'Role')
            ->addRule(Form::FILLED, "`%label` je povinná.")
            ->setItems([
                User::REGISTERED => 'Zaregistrovaný',
                User::ATTENDANT => 'Ošetřovatel',
                User::ADMIN => 'Admin',
            ])
        ;

        if ( ! $this->user->isInRole(User::ADMIN)) {
            $form['role']->setDisabled();
        }

        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'saveUserForm'];

        $this->setDefaults($user, $form);

        return $form;
    }


    public function saveUserForm(Form $form, $values)
    {
        $user = $this->userRepository->find($values->id);

        if ($user->getEmail() != $values->email) {
            $existingUser = $this->userRepository->findBy(['email' => $values->email]);

            if ($existingUser)
                $form->addError('Uživatel s tímto emailem již existuje');
        }

        $user->setEmail($values->email);
        $user->setFirstname($values->firstname);
        $user->setLastname($values->lastname);
        $user->setPersonalNumber($values->personalNumber);
        $user->setBirthday(new Nette\Utils\DateTime($values->birthday));
        $user->setTitle($values->title);
        $user->setRole($values->role);

        $this->userRepository->getEntityManager()->persist($user);
        $this->userRepository->getEntityManager()->flush();
    }

    /**
     * @param User $user
     * @param $form
     */
    private function setDefaults(User $user, $form)
    {
        $form['id']->setDefaultValue($user->getId());
        $form['email']->setDefaultValue($user->getEmail());
        $form['title']->setDefaultValue($user->getTitle());
        $form['firstname']->setDefaultValue($user->getFirstname());
        $form['lastname']->setDefaultValue($user->getLastname());
        $form['personalNumber']->setDefaultValue($user->getPersonalNumber());
        $form['birthday']->setDefaultValue($user->getBirthday() ?  $user->getBirthday()->format('Y-m-d') : NULL);
        $form['role']->setDefaultValue($user->getRole());
    }
}
