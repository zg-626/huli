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


use app\api\logic\IndexLogic;
use app\Request;
use think\response\Json;


/**
 * index
 * Class IndexController
 * @package app\api\controller
 */
class IndexController extends BaseApiController
{


    public array $notNeedLogin = ['index','captcha','getQrcode','getCategory','getDictionary','getSpeak','getBarrage','getBanner', 'config', 'policy', 'decorate'];


    /**
     * @notes 首页数据
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public function index()
    {
        $result = IndexLogic::getIndexData();
        return $this->data($result);
    }

    /**
     * @notes banner
     * @return Json
     * @author esc
     * @date 2023/09/28 09:31
     */
    public function getBanner()
    {
        $result = IndexLogic::getBanner();
        return $this->data($result);
    }

    public function captcha(Request $request)
    {
        $id = mt_rand(100000, 999999);
        $uniqid = uniqid("$id", true);
        //返回数据 验证码图片路径、验证码标识
        $data = [
            'src' => $request->domain().captcha_src($uniqid),
            'uniqid' => $uniqid
        ];
        return $this->data($data);
    }

    /**
     * @notes 收款码
     * @return Json
     * @author esc
     * @date 2023/09/28 09:31
     */
    public function getQrcode()
    {
        $result = IndexLogic::getQrcode();
        return $this->data($result);
    }

    /**
     * @notes 分类
     * @return Json
     * @author esc
     * @date 2023/09/28 09:31
     */
    public function getCategory($pid=0)
    {
        $result = IndexLogic::getCategory($pid);
        return $this->data($result);
    }

    // 获取数据字典
    public function getDictionary($dict_id = 0)
    {
        $result = IndexLogic::getDictionary($dict_id);
        return $this->data($result);
    }

    /**
     * @notes 发声列表
     * @return Json
     * @author esc
     * @date 2023/09/28 09:31
     */
    public function getSpeak()
    {
        $result = IndexLogic::getSpeak();
        return $this->data($result);
    }

    /**
     * @notes 弹幕列表
     * @return Json
     * @author esc
     * @date 2023/09/28 09:31
     */
    public function getBarrage()
    {
        $result = IndexLogic::getBarrage();
        return $this->data($result);
    }


    /**
     * @notes 全局配置
     * @return Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:41
     */
    public function config()
    {
        $result = IndexLogic::getConfigData();
        return $this->data($result);
    }


    /**
     * @notes 政策协议
     * @return Json
     * @author 段誉
     * @date 2022/9/20 20:00
     */
    public function policy()
    {
        $type = $this->request->get('type/s', '');
        $result = IndexLogic::getPolicyByType($type);
        return $this->data($result);
    }


    /**
     * @notes 装修信息
     * @return Json
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public function decorate()
    {
        $id = $this->request->get('id/d');
        $result = IndexLogic::getDecorate($id);
        return $this->data($result);
    }


}