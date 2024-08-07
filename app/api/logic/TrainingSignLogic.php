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


use app\cms\model\Training;
use app\common\logic\BaseLogic;
use app\cms\model\TrainingSign;
use app\common\service\FileService;
use think\facade\Db;


/**
 * TrainingSign逻辑
 * Class TrainingSignLogic
 * @package app\api\logic
 */
class TrainingSignLogic extends BaseLogic
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
            TrainingSign::create([
                'user_id' => $params['user_id'],
                'training_id' => $params['training_id']
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
     * @notes 签到
     * @param array $params
     * @return bool
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function sign(array $params): bool
    {
        Db::startTrans();
        try {
            //查询学习班是否需要学习，如果不需要，签到=学习
            $training = Training::where([
                'id' => $params['training_id']
            ])->findOrEmpty();

            if ($training->isEmpty()) {
                throw new \Exception('该学习班不存在');
            }

            if ($training->is_exam === 0) {
                TrainingSign::where([
                    'user_id' => $params['user_id'],
                    'training_id' => $params['training_id']
                ])->update([
                    'is_check' => 1,
                    'check_time' => time(),
                    'is_study' => 1,
                    'study_time' => time()
                ]);
            }

            TrainingSign::where([
                'user_id' => $params['user_id'],
                'training_id' => $params['training_id']
            ])->update([
                'is_check' => 1,
                'check_time' => time()
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
        return TrainingSign::destroy($params['id']);
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
        return TrainingSign::with(['typename'])->withCount(['signups'])->findOrEmpty($params['id'])->toArray();
    }

    public static function studyLists(array $params): array
    {
        return TrainingSign::hasWhere('training', function ($query) {
            $is_exam = 1;
            if (!empty($is_exam)) {
                $query->where('is_exam', $is_exam);
            }
        })->with([
            'training' => function ($query) {
                $query->with('paper');
            }
        ])->where([
            'user_id' => $params['user_id'],
            'is_study' => $params['is_study']

        ])->order('id', 'desc')->select()->toArray();
    }
}