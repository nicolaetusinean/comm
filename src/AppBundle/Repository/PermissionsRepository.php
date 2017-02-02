<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Permissions;
use \Doctrine\ORM\EntityRepository;


class PermissionsRepository extends EntityRepository
{
    /**
     * @param $id
     * @return Permissions
     */
    public function getById($id)
    {
        return $this->find($id);
    }

    /**
     * @param array $filters
     * @param array $order
     * @param int $page
     * @param int $pageSize
     * @return Permissions
     */
    public function getAll($filters = [], $order = ['id' => 'ASC'], $page = 1, $pageSize = null)
    {
        $offset = $pageSize ? $page * $pageSize - 1 : null;
        $results = $this->findBy($filters, $order, $pageSize, $offset);

        return $results;
    }

    public function save($data)
    {
        if (is_array($data)) {
            $data = $this->constructPermissionsFromArray($data);
        }

        if (!$data instanceof Permissions) {
            throw new \Exception('Invalid $data type.');
        }

        $em = $this->getEntityManager();

        if ($data->getId()) {
            $em->merge($data);
        } else {
            $em->persist($data);
        }
        $em->flush();

        return $data;
    }

    public function constructPermissionsFromArray($data)
    {
        if (isset($data['id']) && empty($data['id'])) {
            $obj = $this->getById($data['id']);
            if (empty($obj)) {
                throw new \Exception('Permissions entity was not found.');
            }
        } else {
            $obj = new Permissions();
        }

        $obj->setRead($data['read'])
            ->setWrite($data['write'])
            ->setDelete($data['delete']);

        if (!is_object($data['user'])) {
            if (is_array($data['user'])) {
                $userId = $data['user']['id'];
            } else {
                $userId = $data['user'];
            }

            $user = $this->getUserRepository()->getById($userId);

            if (!$user) {
                throw new \Exception("Permissions was not found.");
            }

            $obj->setUser($user);
        } else {
            $obj->setUser($data['user']);
        }

        if (isset($data['id']) && !empty($data['id'])) {
            $obj->setId($data['id']);
        }

        return $obj;
    }

    /**
     * @return \AppBundle\Repository\UserRepository
     */
    public function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle\Entity\User');
    }
}