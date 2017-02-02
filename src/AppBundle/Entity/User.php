<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    protected $id;
    protected $username;
    protected $role;
    protected $password;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    public function getRoles()
    {
        return [$this->getRole()];
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function toArray()
    {
        return [
            'id'       => $this->getId(),
            'username' => $this->getUsername(),
            'role'     => $this->getRole(),
        ];
    }
}