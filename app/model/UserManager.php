<?php

namespace App\Model;

use App\Entities\User;
use App\Repositories\UserRepository;
use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager implements Nette\Security\IAuthenticator
{
	use Nette\SmartObject;

	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_EMAIL = 'email',
		COLUMN_ROLE = 'role';

    /**
     * @var UserRepository
     */
    private $userRepository;


    public function __construct(UserRepository $userRepository)
	{
        $this->userRepository = $userRepository;
    }


    /**
     * Performs an authentication.
     * @param array $credentials
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

        $user = $this->userRepository->findOneBy(['email' => $username]);

        if (!$user) {
            throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

        } elseif (!Passwords::verify($password, $user->getPassword())) {
            throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

        } elseif (Passwords::needsRehash($user->getPassword())) {
            $user->setPassword(Passwords::hash($password));
            $this->userRepository->getEntityManager()->flush();
        }

		$arrayData = [
		    'id' => $user->getId(),
		    'email' => $user->getEmail(),
		    'firstname' => $user->getFirstname(),
		    'lastname' => $user->getLastname(),
		    'role' => $user->getRole(),
        ];

		return new Nette\Security\Identity($user->getId(), $user->getRole(), $arrayData);
	}


    /**
     * @param Nette\Utils\ArrayHash $data
     * @throws DuplicateNameException
     */
	public function add(Nette\Utils\ArrayHash $data)
	{
        $existingUser = $this->userRepository->findBy(['email' => $data['email']]);

        if ($existingUser) {
            throw new DuplicateNameException;
        }

        $user = new User();
        $user->setFirstname($data->firstname);
        $user->setLastname($data->lastname);
        $user->setEmail($data->email);
        $user->setPassword(Passwords::hash($data->password));
        $user->setRole(User::REGISTERED);

        $this->userRepository->getEntityManager()->persist($user);
        $this->userRepository->getEntityManager()->flush();
	}

}



class DuplicateNameException extends \Exception
{}
