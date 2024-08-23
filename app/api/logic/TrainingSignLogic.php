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


use app\cms\model\AnswerLog;
use app\cms\model\Fees;
use app\cms\model\Paper;
use app\cms\model\Question;
use app\cms\model\Training;
use app\common\logic\BaseLogic;
use app\cms\model\TrainingSign;
use app\mxadmin\model\DictData;
use app\common\service\FileService;
use Exception;
use think\facade\Db;


/**
 * TrainingSign逻辑
 * Class TrainingSignLogic
 * @package app\api\logic
 */
class TrainingSignLogic extends BaseLogic
{


    /**
     * @notes 报名
     * @param array $params
     * @return bool
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function add(array $params): bool
    {
        Db::startTrans();
        try {
            // 查询学习班
            $training = Training::where([
                'id' => $params['training_id']
            ])->find();

            if ($training->isEmpty()) {
                throw new \Exception('该学习班不存在');
            }
            // 判断是否可以报名
            if (strtotime($training->study_time) > time()) {
                throw new \Exception('该学习班未开始');
            }
            // 判断截至时间
            if (strtotime($training->deadline_time) < time()) {
                throw new \Exception('该学习班已结束');
            }
            TrainingSign::create([
                'user_id' => $params['user_id'],
                'training_id' => $params['training_id']
            ]);
            // 如果学习班收费，加入缴费记录
            if ($training->is_toll === 1) {
                // 获取年份
                $year = date('Y');
                // 获取缴费类型
                $category = DictData::where('id', 31)->find();
                Fees::create([
                    'dict_id' => $category->dict_id,
                    'dict_data_id' => $category->id,
                    'user_id' => $params['user_id'], // 替换为你实际的用户ID
                    'status' => 0,
                    'fees_year' => $year,
                    'fees_type' => $category->name,
                ]);
            }

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
                $query->with([
                    'paper' => function ($query) {
                        $query->withCount('questions');
                    }
                ]);
            }
        ])->where([
            'user_id' => $params['user_id'],
            'is_study' => $params['is_study']

        ])->order('id', 'desc')->select()->toArray();
    }

    public static function answer(array $params)
    {
        try {
            // 获取报名信息
            $info=TrainingSign::where('user_id', $params['user_id'])->where('training_id', $params['training_id'])->findOrEmpty();
            if($info->isEmpty()){
                throw new \RuntimeException('报名信息不存在');
            }

            if($info->is_check === 0){
                throw new \RuntimeException('请先签到');
            }

            // 获取试卷的信息
            $paper=Paper::findOrEmpty($params['paper_id']);
            if($paper->isEmpty()){
                throw new \RuntimeException('试卷不存在');
            }

            if($info->is_study === 1){
                throw new \RuntimeException('请勿重复答题');
            }

            ++$paper->answer_count;
            $paper->save();
            // 获取试题列表
            // 获取试题列表，并将试题以题目ID为键存储在哈希表中
            $questionList = Question::where('paper_id', $params['paper_id'])->field('id,answer,select,type,score')
                ->select();
            // 初始化得分和答题记录数组
            $totalScore = 0;
            $answerRecords = [];

            foreach ($params['answers'] as $answer) {
                // 直接从哈希表中获取试题信息
                $question = Question::where('id', $answer['question_id'])->find()->toArray();

                if (!$question) {
                    continue; // 如果找不到对应试题，跳过
                }

                // 初始化答题记录
                $record = [
                    'question_id' => $answer['question_id'],
                    'user_answer' => $answer['user_answer'],
                    'is_correct' => 0,
                    'score_obtained' => 0,
                    'correct_answer' => $question,
                    'question_score' => $question['score'],
                ];

                // 根据题目类型判断答案是否正确
                if ($question['type'] == 1 || $question['type'] == 3) { // 单选题或判断题
                    if ($answer['user_answer'] == $question['answer']) {
                        $record['is_correct'] = true;
                        $record['score_obtained'] = $question['score'];
                        $totalScore += $question['score'];
                    }
                } elseif ($question['type'] == 2) { // 多选题
                    $question['select'] = explode(',', $question['select']);
                    sort($answer['user_answer']); // 排序用户答案，方便比较
                    sort($question['select']); // 排序正确答案，方便比较

                    if ($answer['user_answer'] == $question['select']) {
                        $record['is_correct'] = true;
                        $record['score_obtained'] = $question['score'];
                        $totalScore += $question['score'];
                    }
                }
                // 将答题记录添加到记录数组中
                $answerRecords[] = $record;
            }

            // 将答题记录添加数据库
            foreach ($answerRecords as $value) {
                $record = [
                    'user_id' => $params['user_id'],
                    'training_id' => $params['training_id'],
                    'paper_id' => $params['paper_id'],
                    'question_id' => $value['question_id'],
                    'user_answer' => $value['user_answer'],
                    'is_correct' => $value['is_correct'],
                    'score_obtained' => $value['score_obtained'],
                    'correct_answer' => $value['correct_answer']['content'],
                ];
                AnswerLog::create($record);
            }

            // 保存答题分数到数据
            TrainingSign::where([
                'user_id' => $params['user_id'],
                'training_id' => $params['training_id'],
            ])->update([
                'total_score' => $totalScore,
                'is_study' => 1,
                'study_time' => time(),
                //'answer_records' => $answerRecords,
            ]);

            // 返回总得分和答题记录
            return [
                'total_score' => $totalScore,
                'answer_records' => $answerRecords,
            ];
        }catch (Exception $e){
            self::setError($e->getMessage());
            return false;
        }
    }
}