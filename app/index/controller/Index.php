<?php
namespace app\index\controller;

use app\BaseController;

class Index extends BaseController
{
    public function index(): \think\response\View
    {
        return view();
    }
}
