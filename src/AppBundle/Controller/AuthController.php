<?php

namespace AppBundle\Controller;

use AppBundle\Service\AuthService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


class AuthController
{
    protected $authService;

    public $result = [
        'success' => true,
        'data'    => null,
        'errors'  => [],
    ];

    public $status = 200;

    public function __construct($authService)
    {
        $this->setAuthService($authService);
    }

    public function loginAction(Request $request)
    {

        try {
            $data = $request->request->all();

            $token = null;

            if (isset($data['username']) && isset($data['password'])) {
                // user login
                $token = $this->getAuthService()->login($data['username'], $data['password']);
            } else {
                throw new \Exception('Missing credentials.');
            }

            $this->result['data'] = [
                'token'   => $token,
            ];
        } catch (\Exception $e) {
            $this->result['success'] = false;
            $this->status = 400;
            $this->result['errors'][] = $e->getMessage();
        }
        return new JsonResponse($this->result, $this->status);
    }

    /**
     * @param AuthService $authService
     * @return $this
     */
    public function setAuthService($authService)
    {
        $this->authService = $authService;
        return $this;
    }

    /**
     *
     * @return AuthService;
     */
    public function getAuthService() {
        if (!$this->authService) {
            throw new \Exception('Auth service is not set.');
        }

        return $this->authService;
    }
}