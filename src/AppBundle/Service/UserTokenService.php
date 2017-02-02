<?php
namespace AppBundle\Service;

use AppBundle\Repository\UserTokenRepository;
use AppBundle\Entity\UserToken;

class UserTokenService
{
    protected $userTokenRep;

    public function __construct($userTokenRepository) {
        $this->setUserTokenRepository($userTokenRepository);
    }

    /**
     * @param array $filters
     * @param string $sort
     * @param int $page
     * @param int $pageSize
     * @return UserToken[]
     */
    public function getAll($filters = [], $sort = 'id', $page = 1, $pageSize = null)
    {
        if (!$sort) {
            $sort = ['id' => 'ASC'];
        } else {
            $sort = explode(' ', $sort);
            $sort = count($sort) > 1 ? [$sort[0] => $sort[1]] : [$sort[0] => 'ASC'];
        }

        $results = $this->getUserTokenRepository()->getAll($filters, $sort, $page, $pageSize);
        return $results;
    }

    /**
     * @param string $token
     * @return UserToken
     */
    public function getByApiKey($token)
    {
        $result = $this->getAll(['token' => $token], 'id', 1, 1);
        return empty($result) ? null : $result[0];
    }

    /**
     * @return UserTokenRepository
     */
    public function getUserTokenRepository()
    {
        if (!is_object($this->userTokenRep)) {
            throw new \Exception('UserToken repository was not set.');
        }

        return $this->userTokenRep;
    }

    /**
     * @param UserRepository $userRep
     * @return UserTokenService
     */
    public function setUserTokenRepository($userTokenRep)
    {
        $this->userTokenRep = $userTokenRep;
        return $this;
    }


}