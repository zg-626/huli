<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\cms\model\CmsCategory;
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
        $department = list_to_trees(CmsCategory::getCategoryData(), true);
        return view('', [
            'feestype' => getDictDataId(11),
            'feesyear' => getDictDataId(12),
            'category' => $department,
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

            $nickname = $data['nickname'] ?? '';
            $status = $data['status'] ?? '';
            $fees_type = $data['fees_type'] ?? '';
            $fees_year = $data['fees_year'] ?? '';
            $d_id = $data['d_id'] ?? '';
            $where=[];
            //$where[] = ['m_id', '=', cache('m_id')];
            if ($status != '') {
                $where[] = ['Fees.status', 'in', $status];
            }
            if ($fees_type != '') {
                $where[] = ['Fees.fees_type', 'in', $fees_type];
            }
            if ($fees_year != '') {
                $where[] = ['Fees.fees_year', 'in', $fees_year];
            }

            $list = FeesModel::hasWhere('user' , function ($query) use ($nickname,$d_id) {
                $query->field('id,nickname');
                if (!empty($nickname)) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                }
                if (!empty($d_id)) {
                    $query->whereIn('d_id',$d_id);
                }
            })->with(['user'=> function ($query) {
                $query->with(['hospital','educationalType','positionType','professionalType']);
            }])->where($where)
                ->order('id desc')
                ->paginate($limit);
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
    public function shenhe($id=0)
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
