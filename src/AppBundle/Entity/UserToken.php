<?php
namespace AppBundle\Entity;

class UserToken
{
    protected $id;
    protected $user;
    protected $token;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return UserToken
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return UserToken
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return UserToken
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function toArray()
    {
        return [
            'id'      => $this->getId(),
            'user'    => $this->getUser()->toArray(),
            'token'     => $this->getToken(),
        ];
    }
}