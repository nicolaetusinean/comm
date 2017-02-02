<?php

namespace AppBundle\Repository;

use \Doctrine\ORM\EntityRepository;
use AppBundle\Entity\UserToken;

class UserTokenRepository extends EntityRepository
{
    public function getById($id)
    {
        return $this->find($id);
    }

    /**
     * @param array $filters
     * @param array $order
     * @param int $page
     * @param null $pageSize
     * @return UserToken[]
     */
    public function getAll($filters = [], $order = ['id' => 'ASC'], $page = 1, $pageSize = null)
    {
        $offset = $pageSize ? $page * $pageSize - 1: null;
        $results = $this->findBy($filters, $order, $pageSize, $offset);
        return $results;
    }

    public function delete($id)
    {
        $UserToken = $this->getById($id);

        if (!$UserToken) {
            throw new \Exception('UserToken was not found.');
        }

        $em = $this->getEntityManager();
        $em->remove($UserToken);
        $em->flush();

        return $UserToken;
    }

    public function loadByAPIKey($token)
    {
        $results = $this->getAll([
            'token' => $token,
        ]);

        return empty($results) ? null : $results[0]->getUser();
    }

    public function save($data)
    {
        if (is_array($data)) {
            $data = $this->constructUserTokenFromArray($data);
        }

        if (!$data instanceof UserToken) {
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

    protected function constructUserTokenFromArray($data)
    {
        $obj = new UserToken();
        $obj->setUser($data['user'])
            ->setToken($data['token']);

        if (isset($data['id']) && !empty($data['id'])) {
            $obj->setId($data['id']);
        }

        return $obj;
    }
}