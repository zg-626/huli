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


use app\api\lists\FeesLists;
use app\api\logic\FeesLogic;
use app\api\validate\FeesValidate;
use app\api\validate\InvoiceValidate;
use think\response\Json;


/**
 * Fees控制器
 * Class FeesController
 * @package app\api\controller
 */
class FeesController extends BaseApiController
{


    /**
     * @notes 获取列表
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function lists()
    {
        return $this->dataLists(new FeesLists());
    }


    /**
     * @notes 添加
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function add()
    {
        $params = (new FeesValidate())->post()->goCheck('add');
        $params['user_id'] = $this->userId;
        $result = FeesLogic::add($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(FeesLogic::getError());
    }


    /**
     * @notes 编辑
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function edit()
    {
        $params = (new FeesValidate())->post()->goCheck('edit');
        $result = FeesLogic::edit($params);
        if (true === $result) {
            return $this->success('编辑成功', [], 1, 1);
        }
        return $this->fail(FeesLogic::getError());
    }

    /**增加发票
     * description:有劳写下注释
     * author: esc
     * Date: 2024/8/5 下午1:48
     * @return Json
     */
    public function addInvoice(): Json
    {
        $params = (new InvoiceValidate())->post()->goCheck('add');
        $params['user_id'] = $this->userId;
        $result = FeesLogic::addInvoice($params);
        if (true === $result) {
            return $this->success('添加成功', [], 1, 1);
        }
        return $this->fail(FeesLogic::getError());
    }


    /**
     * @notes 删除
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function delete()
    {
        $params = (new FeesValidate())->post()->goCheck('delete');
        FeesLogic::delete($params);
        return $this->success('删除成功', [], 1, 1);
    }


    /**
     * @notes 获取详情
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function detail()
    {
        $params = (new FeesValidate())->goCheck('detail');
        $result = FeesLogic::detail($params);
        return $this->data($result);
    }

    /**
     * @notes 获取列表
     * @return Json
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function getLists()
    {
        $result = FeesLogic::getLists();
        return $this->data($result);
    }


}