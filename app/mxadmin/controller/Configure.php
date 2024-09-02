<?php

declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\mxadmin\AdminBase;
use app\mxadmin\model\Config;
use think\exception\ValidateException;
use think\facade\Db;

class Configure extends AdminBase
{
    /**
     * 消息
     * @return \think\response\View
     */
    public function index()
    {
        $smstype = Db::name('config')->where('type', 'sms')->where('id', 8)->find();
        //print_r($smstype);exit;
        //print_r(Db::name('config')->where('type', 'sms')->where('id', 8)->find());exit;
        return view('', [
            'sysconf' => Config::getConfigData('system'),
            'storage' => Config::getConfigData('storage'),
            'email' => Config::getConfigData('email'),
            'weixin' => Config::getConfigData('weixin'),
            'wxapp' => Config::getConfigData('wxapp'),
            'wxpay' => Config::getConfigData('wxpay'),
            'sms' => Config::getConfigData('sms'),
            'smstype' => $smstype,
        ]);
    }

    /**
     * 保存配置
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function submit()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Configure.'.$data['typename']);
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            Config::setConfigData($data['typename'], $data);
            return $this->success('保存成功');
        }
    }

    /**
     * 测试邮箱配置
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function emailtest()
    {
        if (request()->isPost()) {
            $mail = input('param.notice_email');
            $emailset = Config::getConfigData('email');
            $code = rand(100000, 999999);
            $result = sendMail($mail, $emailset['subject'], $emailset['body'], $code);
            return $this->success($result);
        }
    }
}
