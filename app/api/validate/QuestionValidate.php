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

namespace app\api\validate;


use app\common\validate\BaseValidate;


/**
 * Question验证器
 * Class QuestionValidate
 * @package app\api\validate
 */
class QuestionValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'position_id' => 'require',
        'department' => 'require',
        'start_time' => 'require',
        'end_time' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'position_id' => '职务',
        'department' => '科室',
        'start_time' => '开始日期',
        'end_time' => '结束日期',
    ];


    /**
     * @notes 添加场景
     * @return QuestionValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneAdd()
    {
        return $this->only(['position_id', 'department', 'start_time', 'end_time']);
    }


    /**
     * @notes 编辑场景
     * @return QuestionValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'position_id', 'department', 'start_time', 'end_time']);
    }


    /**
     * @notes 删除场景
     * @return QuestionValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return QuestionValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}