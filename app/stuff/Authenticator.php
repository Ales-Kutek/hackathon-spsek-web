<?php
/**
 * Authenticator
 */

namespace Authenticator;

use Majkl578\NetteAddons\Doctrine2Identity\Http\UserStorage;
use Nette;
use \Nette\Object;
use \Nette\Security\IAuthenticator;
use \Nette\Security\AuthenticationException;
use \Kdyby\Doctrine\EntityDao;
use \Majkl578\NetteAddons\Doctrine2Identity\Security\FakeIdentity;
use Repository\UserRepository;

/**
 * Authenticator class
 */
class Authenticator extends Object implements IAuthenticator
{
    /**
     * @var \Kdyby\Doctrine\EntityDao $userRepository
     */
    private $userRepository;

    /**
     * @var UserStorage $storage
     */
    private $storage;

    /**
     * @param EntityDao $dao
     * @param UserStorage $storage
     */
    public function __construct(UserRepository $dao,
                                UserStorage $storage)
    {
        $this->userRepository     = $dao;
        $this->storage = $storage;
    }

    /**
     * Provede přihlášení
     * @param  array
     * @return Nette\Security\Identity
     * @throws Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;
        $account = $this->userRepository->getSingleOrNullBy(array('email' => $email));

        if ($account !== NULL) {
            return new FakeIdentity($account->id, 'Entity\User');

            if (password_verify($password, $account->password) === FALSE) {
                throw new AuthenticationException('Chybně zadané heslo.', self::INVALID_CREDENTIAL);
            }
        } else {
            throw new AuthenticationException('Přihlašovací jméno není platné.', self::INVALID_CREDENTIAL);
        }

        return new FakeIdentity($account->id, 'Entity\User');
    }
}