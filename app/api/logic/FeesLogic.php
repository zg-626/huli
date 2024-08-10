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


use app\common\logic\BaseLogic;
use app\cms\model\Fees;
use app\common\service\FileService;
use think\facade\Db;


/**
 * Fees逻辑
 * Class FeesLogic
 * @package app\api\logic
 */
class FeesLogic extends BaseLogic
{


    /**
     * @notes 添加
     * @param array $params
     * @return bool
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            Fees::where([
                'user_id' => $params['user_id'],
                'fees_type' => $params['fees_type'],
                'fees_year' => $params['fees_year'],
            ])->update([
                'fees_type' => $params['fees_type'],
                'fees_year' => $params['fees_year'],
                'fees_time' => $params['fees_time'],
                'money' => $params['money'],
                'way' => $params['way'],
                'image' => $params['image'],
                'remark' => $params['remark'],
                'status' => 1,
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
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function edit(array $params): bool
    {
        Db::startTrans();
        try {
            Fees::where('id', $params['id'])->update([
                'fees_type' => $params['fees_type'],
                'fees_year' => $params['fees_year'],
                'fees_time' => $params['fees_time'],
                'money' => $params['money'],
                'way' => $params['way'],
                'image' => $params['image'],
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
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function delete(array $params): bool
    {
        return Fees::destroy($params['id']);
    }


    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function detail($params): array
    {
        return Fees::findOrEmpty($params['id'])->toArray();
    }


    /**增加发票
     * description:有劳写下注释
     * author: esc
     * Date: 2024/8/5 下午1:48
     * @return bool
     */
    public static function addInvoice(array $params)
    {
        Db::startTrans();
        try {
            Fees::create([
                'user_id' => $params['user_id'],
                'company' => $params['company'],
                'unit' => $params['unit'],
                'project' => $params['project'],
                'mobile' => $params['mobile'],
                'money' => $params['money'],
                'email' => $params['email'],
                'remark' => $params['remark'] ?? '',

            ]);

            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            self::setError($e->getMessage());
            return false;
        }
    }
}