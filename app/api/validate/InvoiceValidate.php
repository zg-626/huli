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
 * Invoice验证器
 * Class InvoiceValidate
 * @package app\api\validate
 */
class InvoiceValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'id' => 'require',
        'company' => 'require',
        'unit' => 'require',
        'project' => 'require',
        'mobile' => 'require',
        'money' => 'require',
        'email' => 'require',
        //'remark' => 'require',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        'company' => '单位名称',
        'unit' => '单位编号',
        'project' => '开票项目',
        'mobile' => '手机号',
        'money' => '开票金额',
        'email' => '邮箱',
        //'remark' => '备注',
    ];


    /**
     * @notes 添加场景
     * @return InvoiceValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneAdd()
    {
        return $this->only(['company', 'unit', 'project', 'mobile', 'money', 'email', 'remark']);
    }


    /**
     * @notes 编辑场景
     * @return InvoiceValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'company', 'unit', 'project', 'mobile', 'money', 'email', 'remark']);
    }


    /**
     * @notes 删除场景
     * @return InvoiceValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return InvoiceValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}