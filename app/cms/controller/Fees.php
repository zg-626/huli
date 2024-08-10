<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\Fees as FeesModel;
use app\mxadmin\model\UserModel;

class Fees extends AdminBase
{
    /**
     * 列表管理
     * @return \think\response\View
     */
    public function index()
    {
        return view('', [
            'feestype' => getDictDataId(11),
            'feesyear' => getDictDataId(12),
        ]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = FeesModel::with(['user'=> function ($query) {
            $query->with(['hospital','educationalType','positionType','professionalType']);
        }])->order('id desc')->paginate($limit);
        if(!$list->isEmpty()){
            $user=[];
            foreach ($list as $k => $v) {
                if (empty($v->user)) {
                    $v->user = $user;
                }
                $v->departmentname= $v->user->departmentname ?? '';
                $v->phone= $v->user->phone ?? '';
                $v->educational_name= $v->user->educational_name ?? '';
                $v->position_name= $v->user->position_name ?? '';
                $v->professional_name= $v->user->professional_name ?? '';
                $v->sex= $v->user->sex ?? '';
            }
        }
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
            /*$msgReadModel = new FeesModel();
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
            },'hospital'])->where($where)
                ->paginate($limit);
            // 遍历用户及其阅读情况
            foreach ($users as $user) {
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
            $result = FeesModel::create($data);

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
            $result = FeesModel::update($data, ['id' => $id]);

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
            $result = FeesModel::update(['status' => $data['status']], ['id' => $id]);
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
            $result = FeesModel::update(['weight' => $data['weight']], ['id' => $id]);
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
            $result = FeesModel::destroy($ids);

            if ($result == true) {
                return $this->success('数据删除成功');
            } else {
                return $this->error('数据删除失败');
            }
        }
    }

    // 审核
    public function shenhe($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }

            $result = FeesModel::whereIn('id', $ids)->update(['status' => $data['status']]);
            if ($result == true) {
                return $this->success('审核成功');
            } else {
                return $this->error('审核失败');
            }
        }
    }
}
