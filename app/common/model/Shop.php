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


use app\common\model\BaseModel;
use think\model\concern\SoftDelete;


/**
 * 店铺模型
 * Class Shop
 * @package app\common\model
 */
class Shop extends BaseModel
{
    use SoftDelete;
    protected $name = 'shop';
    protected $deleteTime = 'delete_time';

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
}