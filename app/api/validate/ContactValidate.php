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
 * Contact验证器
 * Class ContactValidate
 * @package app\api\validate
 */
class ContactValidate extends BaseValidate
{

     /**
      * 设置校验规则
      * @var string[]
      */
    protected $rule = [
        'id' => 'require',
        // 'type' => 'require',
        // 'department' => 'require',
        // 'contact_person' => 'require',
        // 'company_name' => 'require',
        // 'occupation' => 'require',
        // 'inquiry_text' => 'require',
        // 'create_time' => 'require',

    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'id' => 'id',
        // 'type' => 'お問い合わせ種別',
        // 'department' => '所属',
        // 'contact_person' => 'お名前',
        // 'company_name' => '社名・所属',
        // 'occupation' => '職業',
        // 'inquiry_text' => 'ご相談内容',
        // 'create_time' => '留言时间',

    ];


    /**
     * @notes 添加场景
     * @return ContactValidate
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function sceneAdd()
    {
        return $this->only(['type','department','contact_person','company_name','occupation','inquiry_text','create_time']);
    }


    /**
     * @notes 编辑场景
     * @return ContactValidate
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function sceneEdit()
    {
        return $this->only(['id','type','department','contact_person','company_name','occupation','inquiry_text','create_time']);
    }


    /**
     * @notes 删除场景
     * @return ContactValidate
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return ContactValidate
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

}