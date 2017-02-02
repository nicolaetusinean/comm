<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    public $result = [
        'success' => true,
        'data'    => null,
        'errors'  => [],
    ];
    
    public $status = 200;

    protected $userService;

    public function __construct($authService)
    {
        $this->setUserService($authService);
    }
    
    public function getAllAction(Request $request)
    {
        $filters  = $request->get('filters', []);
        $page     = $request->get('page');
        $pageSize = $request->get('pageSize');
        $sort     = $request->get('sort');
        
        try {
            $data = $this->getUserService()->getAll($filters, $sort, $page, $pageSize);
            
            $results = [];
            if (is_array($data)) {
                foreach ($data as $item) {
                    $results[] = $item->toArray();
                }
            }
            
            $this->result['data'] = $results;
        } catch (\Exception $e) {
            $this->result['success'] = false;
            $this->status = 400;
            $this->result['errors'][] = $e->getMessage();
        }
        
        return new JsonResponse($this->result, $this->status);
    }
    
    public function getOneAction(Request $request)
    {
        $id   = $request->get('id');
        
        try {
            $data =  $this->getUserService()->getOne($id);
            
            if (!empty($data)) {
                $this->result['data'] = $data->toArray();
            }
        } catch (\Exception $e) {
            $this->result['success'] = false;
            $this->status = 400;
            $this->result['errors'][] = $e->getMessage();
        }
        
        return new JsonResponse($this->result, $this->status);
    }
    
    public function saveAction(Request $request)
    {
        $id   = $request->get('id');
        
        try {
            $data = $this->getUserService()->save($request->request->all());
            
            if (!empty($data)) {
                $this->result['data'] = $data->toArray();
            }
        } catch (\Exception $e) {
            $this->result['success'] = false;
            $this->status = 400;
            $this->result['errors'][] = $e->getMessage();
        }
        
        return new JsonResponse($this->result, $this->status);
    }

    /**
     * @param \AppBundle\Service\UserService $userService
     * @return $this
     */
    public function setUserService($userService)
    {
        $this->userService = $userService;
        return $this;
    }
    
    /**
     * 
     * @return \AppBundle\Service\UserService;
     */
    public function getUserService() {
        if (!$this->userService) {
            throw new \Exception('User service is not set.');
        }

        return $this->userService;
    }
}