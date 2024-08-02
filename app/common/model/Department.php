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
use app\common\model\user\User;
use think\model\concern\SoftDelete;


/**
 * Department模型
 * Class Department
 * @package app\common\model\feedback
 */
class Department extends BaseModel
{
    use SoftDelete;

    protected $name = 'department';
    protected $deleteTime = 'delete_time';


    /**
     * @notes 关联用户
     * @return \think\model\relation\HasOne
     * @author esc
     * @date 2023/09/18 17:06
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }



}