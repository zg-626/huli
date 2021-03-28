<?php
// +----------------------------------------------------------------------
// | mxAdmin
// +----------------------------------------------------------------------
// | 版权所有 2020~2050 福州目雪科技有限公司 [ http://www.muxue.com.cn ]
// +----------------------------------------------------------------------
// | 演示地址: http://demo.muxue.com.cn
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/muxue2020/mxAdmin
// +----------------------------------------------------------------------
// | Author: 明仔 <350656405@qq.com>    微信号：zlmlovem
// +----------------------------------------------------------------------
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
