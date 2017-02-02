<?php
namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    protected $userRep;

    public function __construct($userRepository, $passwordEncoder) {
        $this->setUserRepository($userRepository);
        $this->setPasswordEncoder($passwordEncoder);
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

        $results = $this->getUserRepository()->getAll($filters, $sort, $page, $pageSize);
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
        if (!is_array($data) && !$data instanceof UserInterface) {
            throw new \Exception('Invalid user type.');
        }

        $requiredFields = ['username', 'role'];
        foreach ($requiredFields as $field) {
            if (is_array($data)) {
                if (!isset($data[$field]) || (isset($data[$field]) && empty($data[$field]))) {
                    throw new \Exception("Field `$field` is required.`");
                }
            } else {
                $getter = 'get' . ucfirst($field);
                if (empty($data->$getter())) {
                    throw new \Exception("Field `$field` is required.`");
                }
            }
        }

        $acceptedRoles = ['ROLE_ADMIN', 'ROLE_USER'];
        $role = is_array($data) ? $data['role'] : $data->getRole();
        if (!in_array($role, $acceptedRoles)) {
            throw new \Exception('Invalid role. Accepted values: ' . implode(', ', $acceptedRoles));
        }

        $username = is_array($data) ? $data['username'] : $data->getUsername();
        $existing = $this->getByUsername($username);

        if ($existing) {
            throw new \Exception('Username already taken.');
        }

        if (is_array($data) && (!isset($data['id']) || (isset($data['id']) && empty($data['id'])))) {
            // generate salt and password
            $user = new User();
            $passwordData = $this->generatePassword($user, $data['password']);
            $data['password'] = $passwordData['password'];
            $data['salt']     = $passwordData['salt'];
        }
        if (is_object($data) && empty($data->getId())) {
            $this->generatePassword($data, $data['password']);
        }

        $rep = $this->getUserRepository();
        $result = $rep->save($data);

        return $result;
    }

    protected function generatePassword(User $user, $plainPassword)
    {
        $salt = uniqid(mt_rand(), true);
        $user->setSalt($salt);
        $password = $this->getPasswordEncoder()->encodePassword($user, $plainPassword);
        $user->setPassword($password);

        return [
            'salt' => $user->getSalt(),
            'password' => $user->getPassword(),
        ];
    }

    public function setPasswordEncoder($passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     *
     * @return \Symfony\Component\Security\Core\Encoder\UserPasswordEncoder
     * @throws \Exception
     */
    public function getPasswordEncoder()
    {
        if (!is_object($this->passwordEncoder)) {
            throw new \Exception('Password encoder was not set.');
        }

        return $this->passwordEncoder;
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        if (!is_object($this->userRep)) {
            throw new \Exception('User repository was not set.');
        }

        return $this->userRep;
    }

    /**
     * @param UserRepository $userRep
     * @return UserService
     */
    public function setUserRepository($userRep)
    {
        $this->userRep = $userRep;
        return $this;
    }


}