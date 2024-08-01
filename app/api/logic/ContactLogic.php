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

namespace app\api\logic;


use app\common\model\Contact;
use app\common\logic\BaseLogic;
use think\facade\Db;


/**
 * Contact逻辑
 * Class ContactLogic
 * @package app\api\logic
 */
class ContactLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            Contact::create([
                'type' => $params['type'],
                'title' => $params['title'],
                'department' => $params['department'],
                'contact_person' => $params['contact_person'],
                'company_name' => $params['company_name'],
                'occupation' => $params['occupation'],
                'website_url' => $params['website_url'],
                'phone_number' => $params['phone_number'],
                'email' => $params['email'],
                'inquiry_text' => $params['inquiry_text'],
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 编辑
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            Contact::where('id', $params['id'])->update([
                'type' => $params['type'],
                'title' => $params['title'],
                'department' => $params['department'],
                'contact_person' => $params['contact_person'],
                'company_name' => $params['company_name'],
                'occupation' => $params['occupation'],
                'website_url' => $params['website_url'],
                'phone_number' => $params['phone_number'],
                'email' => $params['email'],
                'inquiry_text' => $params['inquiry_text'],
            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 删除
     * @param array $params
     * @return bool
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public static function delete(array $params): bool
    {
        return Contact::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public static function detail($params): array
    {
        return Contact::findOrEmpty($params['id'])->toArray();
    }
}