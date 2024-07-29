<?php
namespace app\api\controller;

use app\BaseController;
use think\App;
class Base extends BaseController
{
    public function __construct(App $app)
    {
        parent::__construct($app);      

    }

    
    /**
     * 返回操作成功json
     * @param array $data
     * @return array
     */
    protected function returnSuccess($data = [])
    {
        return $this->renderJson(1, 'success', $data);
	}
	/**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $message
     * @param array $data
     * @return array
     */
    protected function renderJson($code = 0, $message = '', $data = [])
    {
        return json(compact('code', 'message', 'data'));
    }
    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function returnError($message = 'error', $code = 0, $data = [])
    {
        return $this->renderJson($code, $message, $data);
    }

}