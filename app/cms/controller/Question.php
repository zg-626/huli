<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use think\exception\ValidateException;

class Question extends AdminBase
{
    /**
     * 表单弹窗页面
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function form()
    {
        return view();
    }

    /**
     * 文章管理
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($id,$limit=15)
    {
        $list = \app\cms\model\Question::where('paper_id', $id)->order('weight asc,id desc')->append(['typeName'])->paginate($limit);
        if (!$list->isEmpty()) {
            foreach ($list as $key => $value) {
                // 解码 options 字段为关联数组
                $options = json_decode($value['options'], true);

                // 将 options 中的每个选项作为新字段保存
                foreach ($options as $optionKey => $optionValue) {
                    $list[$key][$optionKey] = $optionValue;
                }
                $list[$key]['options'] = $options;
                // 如果 type 等于 2，处理 select 字段（假设 select 是以逗号分隔的字符串）
                if ($value['type'] == 2) {
                    $list[$key]['select'] = explode(',', $value['select']);
                }
            }
        }

        return $this->result($list);
    }

    /**
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function serach($id,$limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.');
            $serach = new \app\cms\model\Question();
            if ($data['cid'] != '') {
                $serach = $serach->where('category_id', $data['cid']);
            }
            if ($data['title'] != '') {
                $serach = $serach->whereLike('title', '%' . $data['title'] . '%');
            }
            if ($data['status'] != '') {
                $serach = $serach->where('status', $data['status']);
            }
            $list = $serach->where('paper_id', $id)->order('weight asc,id desc')->append(['typeName'])->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 添加文章
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');

            $lettersArray = []; // 初始化一个空数组，用来存储单个字母

            foreach ($data as $key => $value) {
                // 检查$key是否为单个字母（即长度为1的字母），并且$value不为空
                if (strlen($key) == 1 && $this->isSingleLetter($key) && !empty($value)) {
                    $lettersArray[$key] = $value; // 将符合条件的单个字母和对应的值存入新数组
                }
            }

            // 现在 $lettersArray 中包含了所有输入字段中的单个字母及其大写形式
            $options=$lettersArray;
            $create=[
                'score' => $data['score'],
                'paper_id' => $data['paper_id'],
                'type' => $data['type'],
                'content' => $data['content'],
                'answer' => $data['answer']??'',
                'select' => $data['select']??'',
                'options' => json_encode($options)
            ];
            $result = \app\cms\model\Question::create($create);
            if ($result == true) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
    }

    function isSingleLetter($input) {
        return ctype_alpha($input) && strlen($input) === 1;
    }

    /**
     * 修改文章
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            //print_r($data);exit();
            $lettersArray = []; // 初始化一个空数组，用来存储单个字母

            foreach ($data as $key => $value) {
                // 检查$key是否为单个字母（即长度为1的字母），并且$value不为空
                if (strlen($key) == 1 && $this->isSingleLetter($key) && !empty($value)) {
                    $lettersArray[$key] = $value; // 将符合条件的单个字母和对应的值存入新数组
                }
            }

            // 现在 $lettersArray 中包含了所有输入字段中的单个字母及其大写形式
            $options=$lettersArray;
            $update=[
                'score' => $data['score'],
                'paper_id' => $data['paper_id'],
                'type' => $data['type'],
                'content' => $data['content'],
                'answer' => $data['answer']??'',
                'select' => $data['select']??'',
                'options' => json_encode($options)
            ];
            $result = \app\cms\model\Question::update($update, ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改状态
     */
    public function edit_state_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = \app\cms\model\Question::update(['status' => $data['status']], ['id' => $id]);
            if ($result == true) {
                return $this->success($data['status'] ? '已启用' : '已禁用');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改排序
     */
    public function edit_weight_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = \app\cms\model\Question::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除文章
     * @param $id
     */
    public function del($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = \app\cms\model\Question::destroy($ids);
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }
}
