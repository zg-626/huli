<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\Training as TrainingModel;
use app\cms\model\CmsCategory;
use app\mxadmin\model\UserModel;
use think\exception\ValidateException;

class Training extends AdminBase
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
        return view('',[
            'trainingtype' => getDictDataId(6),
        ]);
    }

    /**
     * 学习班管理
     * @return \think\response\View
     */
    public function index()
    {
        return view('', [
            'trainingtype' => getDictDataId(6),
        ]);
    }

    /**
     * 报名管理
     * @return \think\response\View
     */
    public function sign()
    {
        return view('sign');
    }

    /**
     * 学习管理
     * @return \think\response\View
     */
    public function study($id)
    {
        $userlist = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $id)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
           ->select();
        return view('study',['userlist'=>$userlist]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = TrainingModel::with(['admin', 'typename'])->withCount(['signups'])->order('weight asc,id desc')->paginate($limit);
        return $this->result($list);
    }

    /**
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function serach($limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.');
            $serach = new TrainingModel();
            if ($data['type'] != '') {
                $serach = $serach->where('type', $data['type']);
            }
            if ($data['title'] != '') {
                $serach = $serach->whereLike('title', '%' . $data['title'] . '%');
            }
            if ($data['status'] != '') {
                $serach = $serach->where('status', $data['status']);
            }
            $list = $serach->with(['admin', 'typename'])-> order('weight asc,id desc')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 添加学习班
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Article');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $data['admin_id'] = getAdminId();

            $result = TrainingModel::create($data);
            if ($result == true) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
    }

    /**
     * 修改学习班
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Article');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }

            $result = TrainingModel::update($data, ['id' => $id]);
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
            $result = TrainingModel::update(['status' => $data['status']], ['id' => $id]);
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
            $result = TrainingModel::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除学习班
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
            $result = TrainingModel::destroy($ids);
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }

    /**
     * 返回报名记录Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_sign($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->sign_status = 1;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }

    /**
     * 返回报名记录Json格式的搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_sign_search($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $data = input('param.');
        $nickname = $data['nickname'] ?? '';
        $where = [];
        if (!empty($nickname)) {
            $where[] = ['u.nickname', 'like', '%' . $nickname . '%'];
        }
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->where($where)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->sign_status = 1;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }

    /**
     * 返回学习记录Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_same_study($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->study_status = 0;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }

    /**
     * 返回学习记录Json格式的搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist_same_study_search($id, int $limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = $id;
        // 查询所有用户及其阅读情况
        /*$users = UserModel::with(['trainingSigns' => function ($query) use ($notificationId) {
            $query->where('training_id', $notificationId)->with('training');
        },'hospital'])
            ->paginate($limit);
        // 遍历用户及其报名情况
        foreach ($users as $user) {
            $user->sign_status = 0;
            $user->sign_time = '';
            $user->training_title = '';
            $user->study_time = '';
            foreach ($user->trainingSigns as $trainingSign) {
                $user->sign_status = 1;
                $user->training_title = $trainingSign->training->title;
                $user->study_time = $trainingSign->training->study_time;
                if ($trainingSign->create_time) {
                    $user->sign_time=$trainingSign->create_time;
                }
            }
        }*/
        $data = input('param.');
        $nickname = $data['nickname'] ?? '';
        $where = [];
        if (!empty($nickname)) {
            $where[] = ['u.nickname', 'like', '%' . $nickname . '%'];
        }
        $users = UserModel::alias('u')
            ->join('training_sign ts', 'ts.user_id = u.id')
            ->join('training t', 't.id = ts.training_id')
            ->join('cms_category c', 'c.id = u.d_id')
            ->where('ts.training_id', $notificationId)
            ->where($where)
            ->field('u.*,ts.create_time as sign_time,t.title as training_title,t.study_time,c.name as departmentname')
            ->paginate($limit);
        if(!$users->isEmpty()){
            foreach ($users as $user){
                $user->study_status = 1;
                $user->sign_time=date('Y-m-d',$user->sign_time);
                $user->study_time=date('Y-m-d',$user->study_time);

            }
        }
        return $this->result($users);
    }
}
