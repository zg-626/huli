<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------


namespace app\api\controller;


use app\api\lists\DepartmentLists;
use app\api\logic\DepartmentLogic;
use app\api\validate\DepartmentValidate;


/**
 * Department控制器
 * Class DepartmentController
 * @package app\api\controller
 */
class DepartmentController extends BaseApiController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function lists()
    {
        return $this->dataLists(new DepartmentLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function add()
    {
        $params = (new DepartmentValidate())->post()->goCheck('add');
        $params['user_id'] = $this->userId;
        $result = DepartmentLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(DepartmentLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function edit()
    {
        $params = (new DepartmentValidate())->post()->goCheck('edit');
        $result = DepartmentLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(DepartmentLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function delete()
    {
        $params = (new DepartmentValidate())->post()->goCheck('delete');
        DepartmentLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function detail()
    {
        $params = (new DepartmentValidate())->goCheck('detail');
        $result = DepartmentLogic::detail($params);
        return $this->data($result);
    }


}