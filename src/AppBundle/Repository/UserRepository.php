<?php
namespace AppBundle\Repository;

use \Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;


class UserRepository extends EntityRepository
{
    /**
     * @param $id
     * @return User
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
     * @return User
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
            $data = $this->constructUserFromArray($data);
        }

        if (!$data instanceof User) {
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

    public function constructUserFromArray($data)
    {
        if (isset($data['id']) && empty($data['id'])) {
            $obj = $this->getById($data['id']);
            if (empty($obj)) {
                throw new \Exception('User was not found.');
            }
        } else {
            $obj = new User();
        }

        $obj->setUsername($data['username'])
            ->setRole($data['role']);

        if (isset($data['password'])) {
            $obj->setSalt($data['salt']);
            $obj->setPassword($data['password']);
        }

        if (isset($data['id']) && !empty($data['id'])) {
            $obj->setId($data['id']);
        }

        return $obj;
    }
}