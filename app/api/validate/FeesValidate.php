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
 * Fees验证器
 * Class FeesValidate
 * @package app\api\validate
 */
class FeesValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'fees_type' => 'require',
        'fees_year' => 'require',
        'fees_time' => 'require',
        'money' => 'require',
        'way' => 'require',
        'image' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'fees_type' => '缴费类型',
        'fees_year' => '缴费年份',
        'fees_time' => '缴费时间',
        'money' => '缴费金额',
        'way' => '缴费方式',
        'image' => '缴费凭证',
    ];


    /**
     * @notes 添加场景
     * @return FeesValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneAdd()
    {
        return $this->only(['fees_type', 'fees_year', 'fees_time', 'money', 'way', 'image']);
    }


    /**
     * @notes 编辑场景
     * @return FeesValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'fees_type', 'fees_year', 'fees_time', 'money', 'way', 'image']);
    }


    /**
     * @notes 删除场景
     * @return FeesValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return FeesValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}