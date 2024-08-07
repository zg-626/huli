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


use app\api\lists\TrainingSignLists;
use app\api\logic\TrainingSignLogic;
use app\api\validate\SignValidate;
use app\api\validate\TrainingSignValidate;
use think\response\Json;


/**
 * TrainingSign控制器
 * Class TrainingSignController
 * @package app\api\controller
 */
class TrainingSignController extends BaseApiController
{


    /**
     * @notes 获取列表
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function lists()
    {
        return $this->dataLists(new TrainingSignLists());
    }

    // 学习列表
    public function studyLists($is_study): Json
    {
        $params['user_id'] = $this->userId;
        $params['is_study'] = $is_study;
        $result = TrainingSignLogic::studyLists($params);

        return $this->success('获取成功', $result, 1, 1);
    }

    //答题
    public function answer()
    {
        $params = (new TrainingSignValidate())->post()->goCheck(null, ['user_id' => $this->userId]);
        $result = TrainingSignLogic::answer($params);

        return $this->success('答题成功', $result, 1, 1);



    }


    /**
     * @notes 报名
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function add()
    {
        $params['user_id'] = $this->userId;
        $params = (new TrainingSignValidate())->post()->goCheck('add',['user_id' => $this->userId]);
        $result = TrainingSignLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(TrainingSignLogic::getError());
    }

    /**
     * @notes 签到
     * @return Json
     * @author 段誉
     * @date 2022/2/17 18:29
     */
    public function sign()
    {
        $params = (new SignValidate())->post()->goCheck(null, ['user_id' => $this->userId]);
        $result = TrainingSignLogic::sign($params);
        if (true === $result) {
            return $this->success('签到成功', [], 1, 1);
        }
        return $this->fail(TrainingSignLogic::getError());
    }
}