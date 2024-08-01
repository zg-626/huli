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


use app\api\lists\ContactLists;
use app\api\logic\ContactLogic;
use app\api\validate\ContactValidate;


/**
 * Contact控制器
 * Class ContactController
 * @package app\api\controller
 */
class ContactController extends BaseApiController
{
    public array $notNeedLogin = ['add', 'edit', 'delete'];

    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function lists()
    {
        return $this->dataLists(new ContactLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function add()
    {
        $params = (new ContactValidate())->post()->goCheck('add');
        $result = ContactLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(ContactLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function edit()
    {
        $params = (new ContactValidate())->post()->goCheck('edit');
        $result = ContactLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(ContactLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function delete()
    {
        $params = (new ContactValidate())->post()->goCheck('delete');
        ContactLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return \think\response\Json
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function detail()
    {
        $params = (new ContactValidate())->goCheck('detail');
        $result = ContactLogic::detail($params);
        return $this->data($result);
    }


}