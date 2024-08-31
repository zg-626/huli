<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\MsgRead as MsgReadModel;
use app\mxadmin\model\UserModel;

class MsgRead extends AdminBase
{
    /**
     * 列表管理
     * @return \think\response\View
     */
    public function index($id)
    {
        cache('m_id',$id);
        return view('', [
            'adtype' => getDictDataId(1),
        ]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        /*$list = MsgReadModel::with('user')->where('m_id',cache('m_id'))->order('id desc')->paginate($limit);
        return $this->result($list);*/
        $notificationId = cache('m_id');
        // 查询所有用户及其阅读情况
        $users = UserModel::with(['msgReads' => function ($query) use ($notificationId) {
            $query->where('m_id', $notificationId);
        }])
            ->paginate($limit);
        // 遍历用户及其阅读情况
        foreach ($users as $user) {
            $user->departmentname= getDidName($user->d_id) ?? '';
            $user->read_status = 0;
            $user->read_time = null;
            foreach ($user->msgReads as $msgRead) {
                $user->read_status = 1;
                if ($msgRead->create_time) {
                    $user->read_time=$msgRead->create_time;
                }
            }
        }
        return $this->result($users);
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
            /*$msgReadModel = new MsgReadModel();
            $nickname = $data['nickname'] ?? '';
            $status = $data['status'] ?? '';

            $list = $msgReadModel::hasWhere('user' , function ($query) use ($nickname) {
                $query->field('id,nickname');
                if (!empty($nickname)) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                }
            })->with('user')->where('m_id',cache('m_id'))
                ->order('id desc')
                ->paginate($limit);


            return $this->result($list);*/
            $nickname = $data['nickname'] ?? '';
            $notificationId = cache('m_id');
            $where = [];
            if (!empty($nickname)) {
                $where[] = ['nickname', 'like', '%' . $nickname . '%'];
            }
            // 查询所有用户及其阅读情况
            $users = UserModel::with(['msgReads' => function ($query) use ($notificationId) {
                $query->where('m_id', $notificationId);
            }])->where($where)
                ->paginate($limit);
            // 遍历用户及其阅读情况
            foreach ($users as $user) {
                $user->departmentname= getDidName($user->d_id) ?? '';
                $user->read_status = 0;
                $user->read_time = null;
                foreach ($user->msgReads as $msgRead) {
                    $user->read_status = 1;
                    if ($msgRead->create_time) {
                        $user->read_time=$msgRead->create_time;
                    }
                }
            }
            return $this->result($users);
        }
    }

    /**
     * 添加
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = MsgReadModel::create($data);

            if ($result == true) {
                return $this->success('数据添加成功');
            } else {
                return $this->error('数据添加失败');
            }
        }
    }

    /**
     * 修改
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = MsgReadModel::update($data, ['id' => $id]);

            if ($result == true) {
                return $this->success('数据修改成功');
            } else {
                return $this->error('数据修改失败');
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
            $result = MsgReadModel::update(['status' => $data['status']], ['id' => $id]);
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
            $result = MsgReadModel::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除
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
            $result = MsgReadModel::destroy($ids);

            if ($result == true) {
                return $this->success('数据删除成功');
            } else {
                return $this->error('数据删除失败');
            }
        }
    }

    /**
     * 开票
     */
    public function open($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = MsgReadModel::whereIn('id', $ids)->update(['status' => 1]);

            if ($result == true) {
                return $this->success('开票成功');
            } else {
                return $this->error('开票失败');
            }
        }
    }

    /**
     * 作废
     */
    public function cancel($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            $result = MsgReadModel::whereIn('id', $ids)->update(['status' => 0]);

            if ($result == true) {
                return $this->success('作废成功');
            } else {
                return $this->error('作废失败');
            }
        }
    }
}
