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

namespace app\api\logic;

use app\common\enum\YesNoEnum;
use app\common\logic\BaseLogic;
use app\common\model\MsgRead;
use app\common\model\Msg;


/**
 * 通知逻辑
 * Class MsgLogic
 * @package app\api\logic
 */
class MsgLogic extends BaseLogic
{

    /**
     * @notes 通知详情
     * @param $articleId
     * @param $userId
     * @return array
     * @author 段誉
     * @date 2022/9/20 17:09
     */
    public static function detail($articleId, $userId)
    {
        // 通知详情
        $article = Msg::getMsgDetailArr($articleId);
        // 关注状态
        $read = MsgRead::isReadMsg($userId, $articleId);
        if(!$read){
            MsgRead::create([
                'user_id' => $userId,
                'm_id' => $articleId
            ]);
        }
        $article['read']=$read;
        return $article;
    }


    /**
     * @notes 加入收藏
     * @param $userId
     * @param $articleId
     * @author 段誉
     * @date 2022/9/20 16:52
     */
    public static function addCollect($articleId, $userId)
    {
        $where = ['user_id' => $userId, 'article_id' => $articleId];
        $collect = MsgCollect::where($where)->findOrEmpty();
        if ($collect->isEmpty()) {
            MsgCollect::create([
                'user_id' => $userId,
                'article_id' => $articleId,
                'status' => YesNoEnum::YES
            ]);
        } else {
            MsgCollect::update([
                'id' => $collect['id'],
                'status' => YesNoEnum::YES
            ]);
        }
    }


    /**
     * @notes 取消收藏
     * @param $articleId
     * @param $userId
     * @author 段誉
     * @date 2022/9/20 16:59
     */
    public static function cancelCollect($articleId, $userId)
    {
        MsgCollect::update(['status' => YesNoEnum::NO], [
            'user_id' => $userId,
            'article_id' => $articleId,
            'status' => YesNoEnum::YES
        ]);
    }


    /**
     * @notes 通知分类
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/23 14:11
     */
    public static function cate()
    {
        return MsgCate::field('id,name')
            ->where('is_show', '=', 1)
            ->order(['sort' => 'desc', 'id' => 'desc'])
            ->select()->toArray();
    }

}