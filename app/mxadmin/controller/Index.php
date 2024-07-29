<?php

declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\mxadmin\AdminBase;

class Index extends AdminBase
{
    /**
     * 无需登录的方法
     * @var array
     */
    protected $noNeedLogin = ['index'];

    /**
     * 无需权限判断的方法
     * @var array
     */
    protected $noNeedAuth = ['index', 'main'];

    /**
     * 显示后台首页
     * @return \think\response\View
     */
    public function index()
    {
        if (!$this->isLogin()) {
            $this->redirect(url('@mxadmin/login'));
        } else {
            return view('index', [
                'sidenav'   =>  list_to_tree(getMenuData()),
                'admininfo'  =>  session('admin_info'),
            ]);
        }
    }

    /**
     * 显示工作台
     * @return \think\response\View
     */
    public function main()
    {
        return view('', [
            'version' => \think\facade\App::version(),
        ]);
    }
}
