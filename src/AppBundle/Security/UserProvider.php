<?php
namespace AppBundle\Security;

use AppBundle\Service\UserService;
use AppBundle\Service\UserTokenService;
use Lcobucci\JWT\Parser;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;


class UserProvider implements UserLoaderInterface, UserProviderInterface
{
    protected $userService;
    protected $userTokenService;

    public function __construct($userService, $userTokenService)
    {
        $this->setUserService($userService);
        $this->setUserTokenService($userTokenService);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->getUserService()->getByUsername($username);
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if ($user instanceof User) {
            return $this->loadUserByUsername($user->getUsername());
        }

        throw new UnsupportedUserException(
            sprintf('Instances of "%s" are not supported.', get_class($user))
        );
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return in_array($class, [
            'AppBundle\Entity\User',
        ]);
    }

    public function loadByAPIKey($apiKey)
    {
        $apiKey = str_replace('Bearer: ', '', $apiKey);
        $token = (new Parser())->parse((string) $apiKey);

        if ($token->hasClaim('user')) {
            $authToken = $this->getUserTokenService()->getByApiKey($apiKey);
            if ($authToken) {
                return $authToken->getUser();
            }
        }

        return null;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        if (!is_object($this->userService)) {
            throw new \Exception('User service was not set.');
        }

        return $this->userService;
    }

    /**
     * @param UserService $userService
     * @return UserProvider
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }

    /**
     * @return UserTokenService
     */
    public function getUserTokenService()
    {
        if (!is_object($this->userTokenService)) {
            throw new \Exception('UserToken service was not set.');
        }

        return $this->userTokenService;
    }

    /**
     * @param UserTokenService $userTokenService
     * @return UserProvider
     */
    public function setUserTokenService($userTokenService)
    {
        $this->userTokenService = $userTokenService;
        return $this;
    }

}