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

namespace app\common\model;

use app\common\enum\YesNoEnum;
use app\common\model\BaseModel;
use app\mxadmin\model\DictData;
use think\model\concern\SoftDelete;

/**
 * 资讯管理模型
 * Class Msg
 * @package app\common\model\article;
 */
class Msg extends BaseModel
{
    //use SoftDelete;

    //protected $deleteTime = 'delete_time';

    // 表名
    protected $name = 'cms_msg';
    /**
     * @notes  获取分类名称
     * @param $value
     * @param $data
     * @return string
     * @author heshihu
     * @date 2022/2/22 9:53
     */
    public function getCateNameAttr($value, $data)
    {
        return DictData::where('id', $data['category_id'])->value('name');
    }

    /**
     * @notes 浏览量
     * @param $value
     * @param $data
     * @return mixed
     * @author 段誉
     * @date 2022/9/15 11:33
     */
    /*public function getClickAttr($value, $data)
    {
        return $data['click'];
    }*/


    /**
     * @notes 设置图片域名
     * @param $value
     * @param $data
     * @return array|string|string[]|null
     * @author 段誉
     * @date 2022/9/28 10:17
     */
    public function getContentAttr($value, $data)
    {
        return get_file_domain($value);
    }


    /**
     * @notes 清除图片域名
     * @param $value
     * @param $data
     * @return array|string|string[]
     * @author 段誉
     * @date 2022/9/28 10:17
     */
    public function setContentAttr($value, $data)
    {
        return clear_file_domain($value);
    }


    /**
     * @notes 获取通知详情
     * @param $id
     * @return array
     * @author 段誉
     * @date 2022/10/20 15:23
     */
    public static function getMsgDetailArr(int $id)
    {
        $article = Msg::where(['id' => $id, 'status' => YesNoEnum::YES])
            ->findOrEmpty();

        if ($article->isEmpty()) {
            return [];
        }

        // 增加点击量
        $article->click += 1;
        $article->save();

        return $article->append(['cateName'])
            ->toArray();
    }

}