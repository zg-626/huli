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


use app\cms\model\TrainingSign;
use app\common\validate\BaseValidate;


/**
 * TrainingSign验证器
 * Class TrainingSignValidate
 * @package app\api\validate
 */
class TrainingSignValidate extends BaseValidate
{

    /**
     * 设置校验规则
     * @var string[]
     */
    protected $rule = [
        'training_id' => 'require|checkSignup',
    ];


    /**
     * 参数描述
     * @var string[]
     */
    protected $field = [
        'training_id' => '学习班',
    ];


    /**
     * @notes 添加场景
     * @return TrainingSignValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneAdd()
    {
        return $this->only(['training_id']);
    }


    /**
     * @notes 编辑场景
     * @return TrainingSignValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneEdit()
    {
        return $this->only(['id', 'total_score']);
    }


    /**
     * @notes 删除场景
     * @return TrainingSignValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDelete()
    {
        return $this->only(['id']);
    }


    /**
     * @notes 详情场景
     * @return TrainingSignValidate
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function sceneDetail()
    {
        return $this->only(['id']);
    }

    // 检测是否报名过
    protected function checkSignup($value, $rule, $data)
    {
        $info=TrainingSign::where('user_id', $data['user_id'])->where('training_id', $value)->find();
        if ($info) {
            return '您已经报名过了';
        }

        return true;
    }

}