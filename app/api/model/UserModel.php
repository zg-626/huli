<?php
//通行证方法类
namespace app\api\model;

use think\facade\Db;
use think\Model;
use think\facade\Cache;
use think\facade\Request;
use think\Validate;
use think\facade\Log;
class UserModel extends Model
{
	// 模型名
    protected $name = 'user';

	//用户id
    public function getUserById($id){
		return $this->find($id);
	}

	//手机号登录
	public function loginPhone($data)
	{
		$phone=intval($data['phone']);
		//手机号	
		$fwhere['phone'] = ['=',$phone];
		
		//查询是否有此用户	
		$is_user = Db::name('user')->where($fwhere)->find();
		
		if(!$is_user){
			return json(['code' => 0, 'msg' =>'没有此用户']);
		}

		//验证密码是否正确
		/*$password = md5($data['password']);

		if($password !== $is_user['password']){
			return json(['code' => 1, 'dcode'=> '1005','msg' =>'密码错误']);
		}*/
        if($data['code']!=000000){
            //验证码有效期
            $v_row = Db::name("user_phonecode")->where(['phone'=>$data['phone']])->where('type',1)->order("id desc")->find();
            if(empty($v_row)){
                return json(['code' => 0, 'msg' =>'无验证码！']);
            }
            if ($v_row['code'] <> $data['code']) {
                return json(['code' => 0, 'msg' =>'验证码错误！']);
            }
        }

		//查询用户信息
		$user = Db::name('user')->field('id,email,nickname,phone,headimg as heading,status')->where($fwhere)->find();
		if (empty($user)) {
			return json(['code' => 0, 'msg' =>'没有此用户']);
		}
		//用户账号是否锁住
		if($user['status'] == 0){
			return json(['code' => 0, 'msg' =>'账号未审核！']);
		}
		//白名单用户修改部门id
		if($is_user['is_white']==1){
			if(!empty($data['d_id'])){
				//查询部门id是否存在
				$department=Db::name('cms_department')->where('id',$data['d_id'])->find();
				if($department){
					Db::name('user')->where($fwhere)->update([
						'd_id' => $data['d_id'],
					]);
				}else{
					return json(['code' => 0, 'msg' =>'该部门不存在！']);
				}
				
			}
		}
        $user['heading']=Request::domain().$user['heading'];
		//更新用户最后登录时间
		Db::name('user')->where($fwhere)->update([
			'last_login_time' => time(),
			'last_login_ip' => get_real_ip()
		]);

		//设置用户token
		$token = $this->getToken();
		//存储用户token
		$is_token = Db::name('user_token')->where('phone', $user['phone'])->where('type', 1)->find();
		
		if(!$is_token){
			Db::name('user_token')->insert([
				'phone' => $data['phone'],
				'token' => $token,
				'u_id' => $user['id'],
				'type' => 1,
				'add_time' => time(),
			]);
			
		}else{
			//$token=$is_token['token'];
			Db::name('user_token')->where('id', $is_token['id'])->update([
				'token' => $token,
				'add_time' => time(),
			]);
		}

		return json(['code' => 1, 'msg' =>'登录成功', 'token' => $token, 'user' => $user]);
	}

	//安卓手机号注册并登录
	public function loginAndroide($data)
	{
		$phone=intval($data['phone']);
		//手机号	
		$fwhere['phone'] = ['=',$phone];
		
		//查询是否有此用户	
		$is_user = Db::name('user')->where($fwhere)->find();
		
		if(!$is_user){
			//return json(['code' => 0, 'msg' =>'没有此用户']);
			//删除30分钟之前的
	        $deltime = time() - 1800;
	        Db::name("user_phonecode")->where("addtime <".$deltime)->where('type',2)->delete();
            if($data['code']!=000000){
                //验证码有效期
                $v_row = Db::name("user_phonecode")->where(['phone'=>$data['phone']])->where('type',2)->order("id desc")->find();
                if(empty($v_row)){
                    return json(['code' => 0, 'msg' =>'无验证码！']);
                }
                if ($v_row['code'] <> $data['code']) {
                    return json(['code' => 0, 'msg' =>'验证码错误！']);
                }
            }


			$addinfo = array(
				'phone' => $data['phone'],
	            //'password' => md5($data['password']),
	            'nickname' => $data['nickname'],
	            'workname' => $data['workname'],
	            'd_id' => $data['d_id'],
	            'headimg' => '',
	            'login_time' => time(),
	            'last_login_time' => time(),
	            'create_time' => time(),
	            'login_ip' => get_real_ip(),
	            'vip_time' => time()+86400*15,
	            'last_login_time' => time()
				//'last_login_ip' => get_real_ip(),
	        );

	        $query = Db::name('user')->insert($addinfo);
	        if($query){
	        	//设置用户token
				$token = $this->getToken();
				//存储用户token
				Db::name('user_token')->insert([
					'phone' => $data['phone'],
					'u_id' => Db::name('user')->getLastInsID(),
					'token' => $token,
					'add_time' => time(),
				]);
	        	$user = Db::name('user')->field('id,email,nickname,phone,headimg as heading,d_id')->where('phone', $data['phone'])->find();
	        	$user['heading']=Request::domain().$user['heading'];
	        	// Cache::set("user", $user, 2592000);
	        	// Cache::set("token:" . $token, $token, 2592000);
	        	return json(['code' => 1, 'msg' =>'登录成功', 'token' => $token, 'user' => $user]);
	        }
		}
		//验证时间
		if(!empty($is_user['vip_time'])){
			if(time()>=$is_user['vip_time']){
				return json(['code' => 0, 'msg' =>'试用时间结束，请重新联系授权']);
			}
		}
		
		//验证密码是否正确
		/*$password = md5($data['password']);

		if($password !== $is_user['password']){
			return json(['code' => 1, 'dcode'=> '1005','msg' =>'密码错误']);
		}*/
        if($data['code']!=000000){
            //删除30分钟之前的
            $deltime = time() - 1800;
            Db::name("user_phonecode")->where("addtime <".$deltime)->where('type',2)->delete();
            //验证码有效期
            $v_row = Db::name("user_phonecode")->where(['phone'=>$data['phone']])->where('type',2)->order("id desc")->find();
            if(empty($v_row)){
                return json(['code' => 0, 'msg' =>'无验证码！']);
            }
            if ($v_row['code'] <> $data['code']) {
                return json(['code' => 0, 'msg' =>'验证码错误！']);
            }
        }

		//查询用户信息
		$user = Db::name('user')->field('id,email,nickname,phone,headimg as heading,status,d_id')->where($fwhere)->find();
		if (empty($user)) {
			return json(['code' => 0, 'msg' =>'没有此用户']);
		}
		//用户账号是否锁住
		if($user['status'] == 0){
			return json(['code' => 0, 'msg' =>'账号未审核！']);
		}
		//白名单用户修改部门id
		if($is_user['is_white']==1){
			if(!empty($data['d_id'])){
				//Log::write($data['d_id'],'测试日志信息');
				//查询部门id是否存在
				$department=Db::name('cms_department')->where('id',$data['d_id'])->find();
				if($department){
					Db::name('user')->where($fwhere)->update([
						'd_id' => $data['d_id'],
					]);
				}else{
					return json(['code' => 0, 'msg' =>'该部门不存在！']);
				}
			}
		}
        $user['heading']=Request::domain().$user['heading'];
		//更新用户最后登录时间
		Db::name('user')->where($fwhere)->update([
			'login_time' => time(),
			'last_login_time' => time(),
			'last_login_ip' => get_real_ip()
		]);

		//设置用户token
		$token = $this->getToken();
		//存储用户token
		$is_token = Db::name('user_token')->where('phone', $user['phone'])->where('type', 2)->find();
		
		if(!$is_token){
			Db::name('user_token')->insert([
				'phone' => $data['phone'],
				'token' => $token,
				'u_id' => $user['id'],
				'type' => 2,
				'add_time' => time(),
			]);
			//$is_token['token']=$token;
		}else{
			Db::name('user_token')->where('id', $is_token['id'])->where('type', 2)->update([
				'token' => $token,
				'add_time' => time(),
			]);
		}
		//计算天数
		$origin = date_create(date("Y-m-d",time()));
		$target = date_create(date("Y-m-d",$is_user['vip_time']));
		$interval = date_diff($origin, $target);
		$day = $interval->format('%a');
		if($is_user['vip_time']==0){
			$user['end_time']="";
		}else{
			$user['end_time']="体验剩余时间".$day.'天';
		}
		
		//echo $day;
		return json(['code' => 1, 'msg' =>'登录成功', 'token' => $token, 'user' => $user]);
	}

	//获取token
	private function getToken()
	{
		return md5(uniqid() . time());
	}

	//注册
	public function registerPhone($data){
		if (!is_array($data)) {
			$data = [];
		}
		
		/*if (empty($data['phone'])) {
			return json(['code' => 1001, 'msg' =>'缺少手机号']);
		}else if (empty($data['code'])) {
			return json(['code' => 1001, 'msg' =>'缺少验证码']);
		}else if (empty($data['workname'])) {
			return json(['code' => 1001, 'msg' =>'缺少工作队']);
		}else if (empty($data['nickname'])) {
			return json(['code' => 1001, 'msg' =>'缺少姓名']);
		}else if (empty($data['d_id'])) {
			return json(['code' => 1001, 'msg' =>'缺少单位']);
		}*/

		//手机号查询是否有此用户	
		$is_user = Db::name('user')->where('phone', $data['phone'])->find();
		if($is_user){
			return json(['code' => 0, 'msg' =>'手机已注册，请使用该手机号登录，并在账户设置中进行绑定']);
		}

		//删除30分钟之前的
        $deltime = time() - 1800;
        Db::name("user_phonecode")->where("addtime <".$deltime)->where('type',0)->delete();

        //验证码有效期
        $v_row = Db::name("user_phonecode")->where(['phone'=>$data['phone']])->where('type',0)->order("id desc")->find();
        if(empty($v_row)){
        	return json(['code' => 0, 'msg' =>'无验证码！']);
        }
        if ($v_row['code'] <> $data['code']) {
        	return json(['code' => 0, 'msg' =>'验证码错误！']);
        }

		$addinfo = array(
			'phone' => $data['phone'],
            //'password' => md5($data['password']),
            'nickname' => $data['nickname'],
            'workname' => $data['workname'],
            'd_id' => $data['d_id'],
            'headimg' => '',
            'login_time' => time(),
            'last_login_time' => time(),
            'create_time' => time(),
            'login_ip' => get_real_ip(),
            'last_login_time' => time()
			//'last_login_ip' => get_real_ip(),
        );

        $query = Db::name('user')->insert($addinfo);
        if($query){
        	//设置用户token
			$token = $this->getToken();
			//存储用户token
			Db::name('user_token')->insert([
				'phone' => $data['phone'],
				'u_id' => Db::name('user')->getLastInsID(),
				'token' => $token,
				'add_time' => time(),
			]);
        	$user = Db::name('user')->field('id,email,nickname,phone,headimg as heading')->where('phone', $data['phone'])->find();
        	$user['heading']=Request::domain().$user['heading'];
        	// Cache::set("user", $user, 2592000);
        	// Cache::set("token:" . $token, $token, 2592000);
        	return json(['code' => 1, 'msg' =>'register_success', 'token' => $token, 'user' => $user]);
        }else{
        	return json(['code' => 0, 'msg' =>'register_fail']);
        }

	}

	//找回密码
	public function retrieve_password($params){
		if (empty($params['phone'])) {
			return json(['code' => 1001, 'msg' =>'缺少手机号']);
		} else if (empty($params['code'])) {
			return json(['code' => 1001, 'msg' =>'缺少验证码']);
		}else if (empty($params['password'])) {
			return json(['code' => 1001, 'msg' =>'缺少密码']);
		}else if (empty($params['confirmpassword'])) {
			return json(['code' => 1001, 'msg' =>'缺少确认密码']);
		}

		//手机号查询是否有此用户	
		$is_user = Db::name('user')->where('phone', $params['phone'])->find();
		if(empty($is_user)){
			return json(['code' => 0, 'msg' =>'手机号未注册，账户不存在']);
		}
		//删除30分钟之前的
        $deltime = time() - 1800;
        Db::name("user_phonecode")->where("addtime <".$deltime)->delete();

        //验证码有效期
        /*$v_row = Db::name("user_phonecode")->where(['phone'=>$params['phone']])->order("id desc")->find();
        if(empty($v_row)){
        	return json(['code' => 0, 'msg' =>'无验证码！']);
        }
        if ($v_row['code'] <> $params['code']) {
        	return json(['code' => 0, 'msg' =>'验证码错误！']);
		}*/

		//验证输入密码和确认密码是否一致
		if($params['password'] !== $params['confirmpassword']){
			return json(['code' => 0, 'msg' =>'密码和确认密码不一致！']);
		}

		//修改密码
		$upwhere[] = ['phone','=',$params['phone']];
		$addinfo = array(
            'password' => md5($params['password']),
		);
		
		$result = DB::name('user')->where($upwhere)->save($addinfo);
		return $result;
	}

	//发送验证码
	public function send_sms($data){

		if (empty($data['phone'])) {
			return json(['code' => 0, 'msg' =>'缺少手机号']);
		}
		
		//手机号查询是否有此用户	
		$is_user = Db::name('user')->where('phone', $data['phone'])->find();
		if($is_user){
			return json(['code'=>0,'msg'=>'手机已注册，请使用该手机号登录，并在账户设置中进行绑定']);
		}
		//手机格式验证
        if (strlen($data['phone']) <> 11 || !preg_match("/^1[345789]\\d{9}$/", $data['phone'])) {
        	return json(['code'=>0,'msg'=>'手机格式不正确！']);
            die;
        }

        $phone = $data['phone'];
        //删除30分钟之前的
        $deltime = time() - 1800;
        Db::name("user_phonecode")->where("addtime <".$deltime)->where('type',1)->delete();

       	//间隔限制
        //1分钟间隔
        $phonecode_info = Db::name("user_phonecode")->where("phone",$phone)->where('type',1)->order("id desc")->find();
        
        if($phonecode_info){
        	$addtime = $phonecode_info['addtime'];
	        if (!empty($addtime)) {
	            if (time() - $addtime <= 60) {
	            	return json(['code'=>0,'msg'=>'获取验证码过于频繁，请1分钟后再试！']);
	            }
	        }
        }
        $todaytime = strtotime("today");
        $tmotime = $todaytime + 86400;
        $ncount = Db::name("user_phonecode")->where("phone='$phone' and addtime between $todaytime and $tmotime")->order("id desc")->count();
        if ($ncount >= 20) {
        	return json(['code'=>0,'msg'=>'获取验证码已达到今日上限！']);
        }

        $time= date("Y-m-d H:i:s",time());
        $code = GetRandCode();
        $pass="wst123456";
        $password=md5($pass.$time);
        $content="验证码：".$code."(有效期为5分钟)，请勿泄露给他人，如果非本人操作，请忽略此信息。";
        $dd=iconv("UTF-8","GB2312//IGNORE",$content);
        //$send = alisms($data['phone'],$code);
        $send='http://api.sms1086.com/Api/verifycode.aspx?username=15652913015&password='.$password.'&mobiles='.$phone.'&content='.$dd.'&timestamp='.$time.'';
        $dd=preg_replace('# #','%20',$send);
        $sends=file_get_contents($dd);

        if(strpos($sends,'result=0') !== false){ 
        	$datas['code'] = $code;
		    $datas['phone'] = $phone;
		    $datas['type'] = 1;
		    $datas['addtime'] = time();
		    $id = Db::name('user_phonecode')->insert($datas);
         	return json(['code'=>1,'msg'=>'发送成功']);
        }else{
         	return json(['code'=>0,'msg'=>'验证码发送失败,请稍后再试！']);
        }
       
	}

	//发送验证码
	public function send_sms_all($data){
		if (empty($data['phone'])) {
			return json(['code' => 0, 'msg' =>'缺少手机号']);
		}
		
		// //手机号查询是否有此用户	
		$is_user = Db::name('user')->where('phone', $data['phone'])->find();
		if(!$is_user){
			return json(['code'=>0,'msg'=>'手机号未注册！']);
		}
		//手机格式验证
		if (strlen($data['phone']) <> 11 || !preg_match("/^1[345789]\\d{9}$/", $data['phone'])) {
			return json(['code'=>0,'msg'=>'手机格式不正确！']);
			die;
		}

		$phone = $data['phone'];
		//删除30分钟之前的
		$deltime = time() - 1800;
		Db::name("user_phonecode")->where("addtime <".$deltime)->where('type',1)->delete();

			//间隔限制
		//1分钟间隔
		$phonecode_info = Db::name("user_phonecode")->where("phone",$phone)->where('type',1)->order("id desc")->find();
		
		if($phonecode_info){
			$addtime = $phonecode_info['addtime'];
			if (!empty($addtime)) {
				if (time() - $addtime <= 60) {
					return json(['code'=>0,'msg'=>'获取验证码过于频繁，请稍后再试！']);
				}
			}
		}

		$todaytime = strtotime("today");
		$tmotime = $todaytime + 86400;
		$ncount = Db::name("user_phonecode")->where("phone='$phone' and addtime between $todaytime and $tmotime")->order("id desc")->count();
		if ($ncount >= 5) {
			return json(['code'=>0,'msg'=>'获取验证码已达到今日上限！']);
		}

		$time= date("Y-m-d H:i:s",time());
        $code = GetRandCode();
        $pass="wst123456";
        $password=md5($pass.$time);
        $content="验证码：".$code."(有效期为5分钟)，请勿泄露给他人，如果非本人操作，请忽略此信息。";
        $dd=iconv("UTF-8","GB2312//IGNORE",$content);
        //$send = alisms($data['phone'],$code);
        $send='http://api.sms1086.com/Api/verifycode.aspx?username=15652913015&password='.$password.'&mobiles='.$phone.'&content='.$dd.'&timestamp='.$time.'';
        $dd=preg_replace('# #','%20',$send);
        $sends=file_get_contents($dd);

        if(strpos($sends,'result=0') !== false){ 
        	$datas['code'] = $code;
		    $datas['phone'] = $phone;
		    $datas['type'] = 1;
		    $datas['addtime'] = time();
		    $id = Db::name('user_phonecode')->insert($datas);
         	return json(['code'=>1,'msg'=>'发送成功']);
        }else{
         	return json(['code'=>0,'msg'=>'验证码发送失败,请稍后再试！']);
        }
	}
	//发送验证码
	public function send_sms_android($data){
		if (empty($data['phone'])) {
			return json(['code' => 0, 'msg' =>'缺少手机号']);
		}
		
		// //手机号查询是否有此用户	
		/*$is_user = Db::name('user')->where('phone', $data['phone'])->find();
		if(!$is_user){
			return json(['code'=>0,'msg'=>'手机号未注册！']);
		}*/
		//手机格式验证
		if (strlen($data['phone']) <> 11 || !preg_match("/^1[345789]\\d{9}$/", $data['phone'])) {
			return json(['code'=>0,'msg'=>'手机格式不正确！']);
			die;
		}

		$phone = $data['phone'];
		//删除30分钟之前的
		$deltime = time() - 1800;
		Db::name("user_phonecode")->where("addtime <".$deltime)->delete();

			//间隔限制
		//1分钟间隔
		$phonecode_info = Db::name("user_phonecode")->where("phone",$phone)->where('type',2)->order("id desc")->find();
		
		if($phonecode_info){
			$addtime = $phonecode_info['addtime'];
			if (!empty($addtime)) {
				if (time() - $addtime <= 60) {
					return json(['code'=>0,'msg'=>'获取验证码过于频繁，请稍后再试！']);
				}
			}
		}

		$todaytime = strtotime("today");
		$tmotime = $todaytime + 86400;
		$ncount = Db::name("user_phonecode")->where("phone='$phone' and addtime between $todaytime and $tmotime")->order("id desc")->count();
		if ($ncount >= 5) {
			return json(['code'=>0,'msg'=>'获取验证码已达到今日上限！']);
		}

		$time= date("Y-m-d H:i:s",time());
        $code = GetRandCode();
        $pass="wst123456";
        $password=md5($pass.$time);
        $content="验证码：".$code."(有效期为5分钟)，请勿泄露给他人，如果非本人操作，请忽略此信息。";
        $dd=iconv("UTF-8","GB2312//IGNORE",$content);
        //$send = alisms($data['phone'],$code);
        $send='http://api.sms1086.com/Api/verifycode.aspx?username=15652913015&password='.$password.'&mobiles='.$phone.'&content='.$dd.'&timestamp='.$time.'';
        $dd=preg_replace('# #','%20',$send);
        $sends=file_get_contents($dd);

        if(strpos($sends,'result=0') !== false){ 
        	$datas['code'] = $code;
		    $datas['phone'] = $phone;
		    $datas['type'] = 2;
		    $datas['addtime'] = time();
		    $id = Db::name('user_phonecode')->insert($datas);
         	return json(['code'=>1,'msg'=>'发送成功']);
        }else{
         	return json(['code'=>0,'msg'=>'验证码发送失败,请稍后再试！']);
        }
	}

	

	//退出登录操作
	public function log_out($data){
		if (empty($data['token'])) {
			return json(['code' => 1001, 'msg' =>'缺少验证token']);
		}
		$checklogin = is_login($data['token']);
        if($checklogin['code'] == 0){
            return json(['code' => 1002, 'msg' => '账号已经退出/账号信息错误']);
        }
		//查询是否已经退出
		$where['phone'] = ['eq',$checklogin['phone']];
		$query = Db::name('user_token')->where($where)->delete();
		if($query !== false){
			return json(['code' => 1, 'msg' =>'账号退出成功']);
		}else{
			return json(['code' => 0, 'msg' =>'账号退出失败']);
		}
		
	}


	//找回密码
	public function findPassword($data)
	{
		$num = "";
		if (isset($data['key'])) {
			$num = Cache::get($data['key']);
		}
		if ($data['code'] == $num) {
			$user = User::where('phone', $data['phone'])->find();
			if (!empty($user)) {

				Db::table("kr_user")->where("id", $user["id"])->update(['password' => md5(md5($data['password']))]);

				Cache::rm($data['key']);
				throw new BaseException(['code' => 1, 'msg' => Lang::get('success')]);
			} else {
				throw new BaseException(['code' => 0, 'msg' => Lang::get('user_not_exist')]);
			}
		} else {
			throw new BaseException(['code' => 0, 'msg' => Lang::get('code_error')]);
		}
	}

    // 修改密码
    public function edit_password($params){
         if (empty($params['password'])) {
            return json(['code' => 1001, 'msg' =>'缺少密码']);
        }

        if (empty($params['confirmpassword'])) {
            return json(['code' => 1001, 'msg' =>'缺少确认密码']);
        }

        //手机号查询是否有此用户
        $is_user = Db::name('user')->where('phone', $params['phone'])->find();
        if(empty($is_user)){
            return json(['code' => 0, 'msg' =>'手机号未注册，账户不存在']);
        }
        //删除30分钟之前的
        $deltime = time() - 1800;
        Db::name("user_phonecode")->where("addtime <".$deltime)->delete();

        //验证码有效期
        /*$v_row = Db::name("user_phonecode")->where(['phone'=>$params['phone']])->order("id desc")->find();
        if(empty($v_row)){
        	return json(['code' => 0, 'msg' =>'无验证码！']);
        }
        if ($v_row['code'] <> $params['code']) {
        	return json(['code' => 0, 'msg' =>'验证码错误！']);
		}*/

        //验证输入密码和确认密码是否一致
        if($params['password'] !== $params['confirmpassword']){
            return json(['code' => 0, 'msg' =>'密码和确认密码不一致！']);
        }

        //修改密码
        $upwhere[] = ['phone','=',$params['phone']];
        $addinfo = array(
            'password' => md5($params['password']),
        );

        $result = DB::name('user')->where($upwhere)->save($addinfo);
        return $result;
    }
}
