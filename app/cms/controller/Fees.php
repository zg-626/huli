<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\cms\model\CmsCategory;
use app\common\model\user\User;
use app\mxadmin\AdminBase;
use app\cms\model\Fees as FeesModel;
use app\mxadmin\model\UserModel;
use think\facade\Db;
use think\Request;

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
        // 判断是否普通管理员
        $group_id = getRuleId();
        // 超级管理员
        if (session('admin_info.is_admin') == 1) {
            $list = FeesModel::with(['user'=> function ($query) {
                $query->with(['educationalType','positionType','professionalType']);
            }])->order('id desc')->paginate($limit);
        }elseif ($group_id === 3 || $group_id === 2) {
            $d_id = session('admin_info.d_id');
            //当前医院下的医院
            $d_ids=get_all_child_cate($d_id);
            if(!empty($d_ids)){
                $d_id=$d_ids.','.$d_id;
            }
            // 先查询所有本医院的用户
            $uids = UserModel::where('d_id', 'in', $d_id)->column('id');
            $list = FeesModel::with(['user'=> function ($query) {
                $query->with(['educationalType','positionType','professionalType']);
            }])->whereIn('user_id', $uids)->order('id desc')->paginate($limit);
        }

        if(!$list->isEmpty()){
            $user=[];
            foreach ($list as $k => $v) {
                if (empty($v->user)) {
                    $v->user = $user;
                }
                $v->d_id= $v->user->d_id ?? 0;
                $v->departmentname= getDidName($v->d_id) ?? '';
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

            // 判断是否普通管理员
            $group_id = getRuleId();
            // 超级管理员
            if (session('admin_info.is_admin') == 1) {
                $list = FeesModel::hasWhere('user' , function ($query) use ($nickname,$d_id) {
                    $query->field('id,nickname');
                    if (!empty($nickname)) {
                        $query->where('nickname', 'like', '%' . $nickname . '%');
                    }
                    if (!empty($d_id)) {
                        $query->whereIn('d_id',$d_id);
                    }
                })->with(['user'=> function ($query) {
                    $query->with(['educationalType','positionType','professionalType']);
                }])->where($where)
                    ->order('id desc')
                    ->paginate($limit);
            }elseif ($group_id === 3 || $group_id === 2) {
                $d_id = session('admin_info.d_id');
                //当前医院下的医院
                $d_ids=get_all_child_cate($d_id);
                if(!empty($d_ids)){
                    $d_id=$d_ids.','.$d_id;
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
                    $query->with(['educationalType','positionType','professionalType']);
                }])->where($where)
                    ->order('id desc')
                    ->paginate($limit);
            }

            if(!$list->isEmpty()){
                $user=[];
                foreach ($list as $k => $v) {
                    if (empty($v->user)) {
                        $v->user = $user;
                    }
                    $v->d_id= $v->user->d_id ?? 0;
                    $v->departmentname= getDidName($v->d_id) ?? '';
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
                // 更新记录
                // 1. 查询数据库获取用户和费用数据
                $fees = FeesModel::whereIn('id', $ids)->select()->toArray();
                $users = User::whereIn('id', $ids)->select();

                // 2. 处理费用数据
                $feesMap = [];
                foreach ($fees as $fee) {
                    $feesMap[$fee['user_id']][] = $fee['fees_year'];
                }

                // 3. 遍历用户数据并更新每个用户的 vip_year
                foreach ($users as $user) {
                    if (isset($feesMap[$user->id])) {
                        $newVipYear = $user->vip_year ? $user->vip_year . ',' . implode(',', $feesMap[$user->id]) : implode(',', $feesMap[$user->id]);
                        User::where('id', $user->id)->update(['vip_year' => $newVipYear]);
                    }
                }


                return $this->success('审核成功');
            } else {
                return $this->error('审核失败');
            }
        }
    }

    /** @DESC [导入缴费] */
    public function daoru(Request $request)
    {
        // 接收文件上传信息
        $file = $request->file("file");
        // 调用类库，读取excel中的内容
        $training = new Training($this->app);
        $excel_array = $training->importExcel($file);

        $data = [];
        foreach ($excel_array as $key => $value) {
            $number = $key + 2;
            // 正则去除多余空白字符
            $phone = preg_replace('/\s+/', '', $value['1']);
            $value2 = preg_replace('/\s+/', '', $value['2']);
            $value3 = preg_replace('/\s+/', '', $value['3']);
            $value4 = preg_replace('/\s+/', '', $value['4']);
            $value5 = preg_replace('/\s+/', '', $value['5']);
            $value6 = preg_replace('/\s+/', '', $value['6']);
            $value7 = preg_replace('/\s+/', '', $value['7']);

            if ($value3 !== '缴费') {
                return $this->error('缴费状态有误，请检查导入类型');
            }

            // 查询用户是否存在
            $userinfo = Db::name('user')->where('phone', $phone)->find();
            if (!$userinfo) {
                return $this->error('用户手机号不存在，请检查，在第' . $number . '行');
            }

            // 查询学习班详情
            /*$info = \app\cms\model\Training::where('id', $value4)->find();
            if (!$info) {
                return $this->error('学习班不存在，请检查');
            }*/

            $updateData = [
                'status' => 1,
                'fees_time' => $value2,
                'fees_type' => $value4,
                'fees_year' => $value5,
                'money' => $value6,
                'way' => $value7,
                'image' => '/storage/images/EaJJbAZUrb.png'
            ];

            $data[] = $updateData;
        }
        $status = false;  //定义状态
        // 启动事务
        Db::startTrans();
        try {
            // 批量更新
            foreach ($data as $item) {
                Db::name('fees')->where([
                    'user_id' => $userinfo['id'], // 假设 user_id 是关联字段
                    'fees_type' => $item['fees_type'],
                    'fees_year' => $item['fees_year'],
                ])->update($item);
            }

            // 提交事务
            Db::commit();
            $status = true;
        } catch (Throwable $t) {
            // 回滚事务
            Db::rollback();
            return $this->error('导入数据失败: ' . $t->getLine());
        }

        if($status){
            return $this->success('文件上传成功，已经导入' . count($data) . '条数据');
        }

        return false;
    }
}
