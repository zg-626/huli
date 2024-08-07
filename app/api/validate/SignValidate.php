<?php

namespace app\api\validate;

use app\cms\model\TrainingSign;
use app\common\validate\BaseValidate;

/**
 * 签到验证
 * Class Sign
 * @package app\api\validate
 */
class SignValidate extends BaseValidate
{
    protected $rule = [
        //'user_id' => 'checkSign',
        'training_id' => 'require|checkSign',
    ];

    public function checkSign($value, $rule, $data)
    {
        $today = TrainingSign::where(['user_id' => $data['user_id'], 'training_id' => $value,'is_check' => 1])
            ->findOrEmpty();

        if (!$today->isEmpty()) {
            return '您已签到过了';
        }

        return true;
    }
}