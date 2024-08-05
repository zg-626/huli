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


use app\api\lists\TrainingLists;
use app\api\logic\TrainingLogic;
use app\api\validate\TrainingValidate;


/**
 * Training控制器
 * Class TrainingController
 * @package app\api\controller
 */
class TrainingController extends BaseApiController
{


    /**
     * @notes 获取列表
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function lists()
    {
        return $this->dataLists(new TrainingLists());
    }


    /**
     * @notes 添加
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function add()
    {
        $params = (new TrainingValidate())->post()->goCheck('add');
        $params['user_id'] = $this->userId;
        $result = TrainingLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(TrainingLogic::getError());
    }


    /**
     * @notes 编辑
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function edit()
    {
        $params = (new TrainingValidate())->post()->goCheck('edit');
        $result = TrainingLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(TrainingLogic::getError());
    }


    /**
     * @notes 删除
     * @return \think\response\Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function delete()
    {
        $params = (new TrainingValidate())->post()->goCheck('delete');
        TrainingLogic::delete($params);
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
        $params = (new TrainingValidate())->goCheck('detail');
        $result = TrainingLogic::detail($params);
        return $this->data($result);
    }


}