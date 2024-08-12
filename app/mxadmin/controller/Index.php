<?php

declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\cms\model\CmsCategory;
use app\mxadmin\AdminBase;
use app\mxadmin\model\Dict;
use app\mxadmin\model\DictData;
use think\facade\App;

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
        $ageData = [
            '20岁已下',
            '20-30岁',
            '30-40岁',
            '40-50岁',
            '50-60岁',
            '60岁以上',
            '未知'
        ];
        $professional= DictData::where(['dict_id' => 4, 'status' => 1])->order('weight,id')->column('name');
        $qualifications= DictData::where(['dict_id' => 2, 'status' => 1])->order('weight,id')->column('name');
        $professionalOpen = Dict::where('id', 4)->value('stats');
        $qualificationsOpen = Dict::where('id', 2)->value('stats');
        $ageOpen = Dict::where('id', 13)->value('stats');
        $department = list_to_trees(CmsCategory::getCategoryData(), true);
        //print_r($professional);exit();
        return view('', [
            'version' => App::version(),
            'category' => $department,
            'yeartype' => getDictDataId(12),
            'professional' => $professional,//职称
            'qualifications' => $qualifications,//学历
            'age' => $ageData,
            'professionalOpen' => $professionalOpen,
            'qualificationsOpen' => $qualificationsOpen,
            'ageOpen' => $ageOpen
        ]);
    }
}
