<?php
namespace AppBundle\Service;

use AppBundle\Repository\PermissionsRepository;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissionsService
{
    protected $permissionsRep;

    public function __construct($permissionsRepository) {
        $this->setPermissionsRepository($permissionsRepository);
    }

    /**
     * @param array $filters
     * @param string $sort
     * @param int $page
     * @param int $pageSize
     * @return User[]
     */
    public function getAll($filters = [], $sort = 'id', $page = 1, $pageSize = null)
    {
        if (!$sort) {
            $sort = ['id' => 'ASC'];
        } else {
            $sort = explode(' ', $sort);
            $sort = count($sort) > 1 ? [$sort[0] => $sort[1]] : [$sort[0] => 'ASC'];
        }

        $results = $this->getPermissionsRepository()->getAll($filters, $sort, $page, $pageSize);
        return $results;
    }

    /**
     * @param string $username
     * @return User
     */
    public function getByUsername($username)
    {
        $result = $this->getAll(['username' => $username], 'id', 1, 1);
        return empty($result) ? null : $result[0];
    }

    public function save($data)
    {
        $rep = $this->getPermissionsRepository();
        $result = $rep->save($data);

        return $result;
    }

    /**
     * @return PermissionsRepository
     */
    public function getPermissionsRepository()
    {
        if (!is_object($this->permissionsRep)) {
            throw new \Exception('Permissions repository was not set.');
        }

        return $this->permissionsRep;
    }

    /**
     * @param PermissionsRepository $permissionsRep
     * @return PermissionsService
     */
    public function setPermissionsRepository($permissionsRep)
    {
        $this->permissionsRep = $permissionsRep;
        return $this;
    }




}