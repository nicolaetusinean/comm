<?php
namespace AppBundle\Entity;


class Permissions
{
    protected $id;
    protected $user;
    protected $read;
    protected $write;
    protected $delete;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Permissions
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
     * @return Permissions
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRead()
    {
        return $this->read;
    }

    /**
     * @param mixed $read
     * @return Permissions
     */
    public function setRead($read)
    {
        $this->read = $read;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWrite()
    {
        return $this->write;
    }

    /**
     * @param mixed $write
     * @return Permissions
     */
    public function setWrite($write)
    {
        $this->write = $write;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDelete()
    {
        return $this->delete;
    }

    /**
     * @param mixed $delete
     * @return Permissions
     */
    public function setDelete($delete)
    {
        $this->delete = $delete;
        return $this;
    }

    public function toArray()
    {
        return [
            'user'   => $this->getUser()->toArray(),
            'read'   => $this->getRead(),
            'write'  => $this->getWrite(),
            'delete' => $this->getDelete(),
        ];
    }
}