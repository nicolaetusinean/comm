<?php
namespace AppBundle\Service;


use AppBundle\Repository\UserTokenRepository;
use AppBundle\Entity\UserToken;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class AuthService
{
    protected $userService;
    protected $usertokenRep;
    protected $token;

    public function __construct($userService, $userTokenRepository)
    {
        $this->setUserService($userService);
        $this->setUserTokenRepository($userTokenRepository);
    }

    public function getCurrentUser()
    {
        if ($this->token) {
            $token = (new Parser())->parse((string)$this->getToken());

            return (array)$token->getClaim('user');
        }

        return null;
    }


    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    protected function getSignatureKey()
    {
        $authConfig = [
            'signature_key' => 'asnjc121bsajh121b5',
        ];
        return $authConfig['signature_key'];
    }

    protected function getSigner()
    {
        $signer = new Sha256();
        return $signer;
    }

    public function login($username, $password)
    {
        $userService = $this->getUserService();
        $user = $userService->getByUsername($username);

        if (empty($user)) {
            throw new \Exception('Invalid user.');
        }


        if (!password_verify($password,  $user->getPassword())) {
            throw new \Exception('Invalid password.');
        }

        $signer    = $this->getSigner();
        $signature = $this->getSignatureKey();

        $userData = $user->toArray();
        $token = (new Builder())->setIssuer($_SERVER['SERVER_NAME']) // Configures the issuer (iss claim)
//                                ->setAudience('http://example.org') // Configures the audience (aud claim)
                ->setId('4f1g23a12aa', true) // Configures the id (jti claim), replicating as a header item
                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                ->setNotBefore(time() + 10) // Configures the time that the token can be used (nbf claim)
                ->setExpiration(time() + 3600 * 24 * 30) // Configures the expiration time of the token (nbf claim)
                ->set('user', $userData)
                ->sign($signer, $signature)
                ->getToken(); // Retrieves the generated token

        $userToken = new UserToken();
        $userToken->setToken($token->__toString())
                  ->setUser($user);

        $userToken = $this->getUserTokenRepository()->save($userToken);

        if (!$userToken->getId()) {
            throw new \Exception('Some error occured.');
        }

        return $token->__toString();
    }

    public function validateToken($token)
    {
        $userToken = $this->getUserTokenModel()->getAll(['token' => (string) $token]);
        if (empty($userToken)) {
            throw new \Exception('Token is not assigned to any user.');
        }

        $token = (new Parser())->parse((string) $token);

        if ($token->isExpired()) {
            throw new \Exception('JWT token has expired.');
        }

        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer($_SERVER['SERVER_NAME']);

        $validity = $token->validate($data);

        if ($validity) {
            $signer    = $this->getSigner();
            $signature = $this->getSignatureKey();

            $validity = $token->verify($signer, $signature);
        }

        return $validity;
    }

    public function logout()
    {
        $user = $this->getCurrentUser();
        $this->getUserTokenModel()->deleteAll(['user_id' => $user['UserId']]);
        return true;
    }

    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        if (!$this->userService) {
            throw new \Exception('User service is not set.');
        }

        return $this->userService;
    }

    /**
     * @return UserTokenRepository
     */
    public function getUserTokenRepository()
    {
        return $this->usertokenRep;
    }

    /**
     * @param UserTokenRepository $usertokenRep
     * @return AuthService
     */
    public function setUserTokenRepository($usertokenRep)
    {
        $this->usertokenRep = $usertokenRep;
        return $this;
    }


}