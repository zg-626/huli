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


use app\api\lists\article\ArticleCollectLists;
use app\api\lists\MsgLists;
use app\api\logic\MsgLogic;

/**
 * 通知管理
 * Class ArticleController
 * @package app\api\controller
 */
class MsgController extends BaseApiController
{

    public array $notNeedLogin = ['lists', 'cate', 'detail'];


    /**
     * @notes 通知列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 15:30
     */
    public function lists()
    {
        return $this->dataLists(new MsgLists());
    }


    /**
     * @notes 通知分类列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 15:30
     */
    public function cate()
    {
        return $this->data(MsgLogic::cate());
    }


    /**
     * @notes 收藏列表
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 16:31
     */
    public function collect()
    {
        return $this->dataLists(new ArticleCollectLists());
    }


    /**
     * @notes 通知详情
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 17:09
     */
    public function detail()
    {
        $id = $this->request->get('id/d');
        $result = MsgLogic::detail($id, $this->userId);
        return $this->data($result);
    }


    /**
     * @notes 加入收藏
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 17:01
     */
    public function addCollect()
    {
        $articleId = $this->request->post('id/d');
        MsgLogic::addCollect($articleId, $this->userId);
        return $this->success('操作成功');
    }


    /**
     * @notes 取消收藏
     * @return \think\response\Json
     * @author 段誉
     * @date 2022/9/20 17:01
     */
    public function cancelCollect()
    {
        $articleId = $this->request->post('id/d');
        MsgLogic::cancelCollect($articleId, $this->userId);
        return $this->success('操作成功');
    }


}