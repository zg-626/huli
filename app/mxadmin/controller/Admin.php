<?php

declare (strict_types = 1);

namespace app\mxadmin\controller;

use app\cms\model\CmsCategory;
use app\mxadmin\AdminBase;
use app\mxadmin\model\AdminModel;
use app\mxadmin\model\AuthGroup;
use app\mxadmin\model\AuthGroupAccess;
use think\exception\ValidateException;

class Admin extends AdminBase
{
    /**
     * 账号管理
     * @return \think\response\View
     */
    public function index()
    {
        // 获取角色列表
        $department = list_to_trees(CmsCategory::getCategoryData(), true);
        $rolelist = AuthGroup::field('id,title')->order('id')->select();
        return view('',[
            'rolelist'  =>  $rolelist,
            'admin_id' => getAdminId(),
            'category' => $department,
            'educational_type' => getDictDataId(2),
            'position_type' => getDictDataId(3),
            'professional_type' => getDictDataId(4),
        ]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = AdminModel::with(['roles','user'=> function ($query) {
            $query->with(['hospital','educationalType','positionType','professionalType']);
        }])->order('id', 'desc')->paginate($limit);
        if(!$list->isEmpty()){
            $user=[];
            foreach ($list as $k => $v) {
                if (empty($v->user)) {
                    $v->user = $user;
                }
                $v->departmentname= $v->user->departmentname ?? '';
                $v->educational_name= $v->user->educational_name ?? '';
                $v->position_name= $v->user->position_name ?? '';
                $v->professional_name= $v->user->professional_name ?? '';
                $v->sex= $v->user->sex ?? '';
            }
        }
        return $this->result($list);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function role_datalist($id,$limit=15)
    {
        // 查询当前组所有用户
        $uids = AuthGroupAccess::where('group_id', $id)->column('uid');

        $list = AdminModel::with(['roles','user'=> function ($query) {
            $query->with(['hospital','educationalType','positionType','professionalType']);
        }])->whereIn('id', $uids)->order('id', 'desc')->paginate($limit);
        if(!$list->isEmpty()){
            $user=[];
            foreach ($list as $k => $v) {
                if (empty($v->user)) {
                    $v->user = $user;
                }
                $v->departmentname= $v->user->departmentname ?? '';
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
            $role_id = $data['role_id'] ?? '';
            $d_id = $data['d_id'] ?? '';
            $phone = $data['phone'] ?? '';
            $professional_id = $data['professional_id'] ?? '';
            $educational_id = $data['educational_id'] ?? '';
            $where = [];
            if ($data['status'] != '') {
                $where['status'] = $data['status'];
            }
            if ($data['phone'] != '') {
                $where['username'] = $data['phone'];
            }
            if($role_id){
                // 查询当前组所有用户
                $uids = AuthGroupAccess::whereIn('group_id', $role_id)->column('uid');
                if($uids){
                    // 转换数组为逗号分隔的字符串
                    $uids_str = implode(',', $uids);
                    $where['mx_admin.id'] = ['in', $uids_str];
                }

            }
            $list = AdminModel::hasWhere('user' , function ($query) use ($nickname,$phone,$d_id,$educational_id,$professional_id) {
                $query->field('id,nickname');
                if (!empty($nickname)) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                }
                /*if (!empty($phone)) {
                    $query->where('phone', $phone);
                }*/
                if (!empty($d_id)) {
                    $query->whereIn('d_id',$d_id);
                }
                if (!empty($educational_id)) {
                    $query->whereIn('educational_id',$educational_id);
                }
                if (!empty($professional_id)) {
                    $query->whereIn('professional_id',$professional_id);
                }
            })->with(['roles','user'=> function ($query) {
                $query->with(['hospital','educationalType','positionType','professionalType']);
            }])->where($where)->order('id', 'desc')->paginate($limit);
            if(!$list->isEmpty()){
                $user=[];
                foreach ($list as $k => $v) {
                    if (empty($v->user)) {
                        $v->user = $user;
                    }
                    $v->departmentname= $v->user->departmentname ?? '';
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
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function role_datalist_serach($id,$limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.');
            // 查询当前组所有用户
            $uids = AuthGroupAccess::where('group_id', $id)->column('uid');
            $where = [];
            if ($data['status'] != '') {
                $where['status'] = $data['status'];
            }
            if ($data['phone'] != '') {
                $where['username'] = $data['phone'];
            }
            $nickname = $data['nickname'] ?? '';
            $list = AdminModel::hasWhere('user' , function ($query) use ($nickname) {
                $query->field('id,nickname');
                if (!empty($nickname)) {
                    $query->where('nickname', 'like', '%' . $nickname . '%');
                }
            })->with(['roles','user'=> function ($query) {
                $query->with(['hospital','educationalType','positionType','professionalType']);
            }])->whereIn('id', $uids)->where($where)->order('id', 'desc')->paginate($limit);
            if(!$list->isEmpty()){
                $user=[];
                foreach ($list as $k => $v) {
                    if (empty($v->user)) {
                        $v->user = $user;
                    }
                    $v->departmentname= $v->user->departmentname ?? '';
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
     * 添加账号
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Admin');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $data['password'] = $data['newpassword'];
            $result = AdminModel::create($data);

            if ($result == true) {
                // 新增用户所属角色
                $role_id = explode(',',$data['role_id']);
                foreach ($role_id as $value) {
                    $dataset[] = ['uid' => $result->id, 'group_id' => $value];
                }
                AuthGroupAccess::insertAll($dataset);

                return $this->success('账号添加成功');
            } else {
                return $this->error('账号添加失败');
            }
        }
    }

    /**
     * 修改账号
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Admin.edit');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            if (empty($data['newpassword'])) {
                unset($data['password']);
            } else {
                $data['password'] = $data['newpassword'];
            }
            $result = AdminModel::update($data, ['id' => $id]);

            if ($result == true) {
                // 先移除用户所属角色
                AuthGroupAccess::where('uid', $id)->delete();
                // 再新增用户所属角色
                $role_id = explode(',', $data['role_id']);
                foreach ($role_id as $value) {
                    $dataset[] = ['uid' => $id, 'group_id' => $value];
                }
                AuthGroupAccess::insertAll($dataset);

                return $this->success('账号修改成功');
            } else {
                return $this->error('账号修改失败');
            }
        }
    }

    /**
     * 修改账号状态
     */
    public function edit_state_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $value = AdminModel::where('id', $id)->find();
            if ($id == getAdminId()) {
                return $this->error('自已账号禁止禁用');
            }
            if ($value['is_admin'] == 1) {
                return $this->error('超级管理员禁止禁用');
            }
            $result = AdminModel::update(['status' => $data['status']], ['id' => $id]);

            if ($result == true) {
                return $this->success($data['status'] ? '已启用' : '已禁用');
            } else {
                return $this->error('账号状态修改失败');
            }
        }
    }

    /**
     * 删除账号
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
            $idsdata = AdminModel::whereIn('id', $ids)->select();
            foreach($idsdata as $value){
                if ($value['id'] == getAdminId()) {
                    return $this->error('自己账号禁止删除');
                } elseif ($value['is_admin'] == 1) {
                    return $this->error('超级管理员禁止删除');
                }
            }
            $result = AdminModel::destroy($ids);

            if ($result == true) {
                // 删除用户所属角色
                AuthGroupAccess::whereIn('uid', $ids)->delete();

                return $this->success('账号删除成功');
            } else {
                return $this->error('账号删除失败');
            }
        }
    }

    /**
     * 重置密码
     */
    public function reset($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }
            //$idsdata = AdminModel::whereIn('id', $ids)->select();
            /*foreach($idsdata as $value){
                if ($value['id'] == getAdminId()) {
                    return $this->error('自己账号禁止删除');
                } elseif ($value['is_admin'] == 1) {
                    return $this->error('超级管理员禁止删除');
                }
            }*/
            $data['password'] = 'Yj@123456';

        $result = AdminModel::update($data)->whereIn('id', $ids);

            if ($result == true) {

                return $this->success('密码重置成功');
            } else {
                return $this->error('密码重置失败');
            }
        }
    }
}
