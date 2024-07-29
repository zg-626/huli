<?php

declare (strict_types = 1);

namespace app\mxadmin\validate;

use think\Validate;

class Dict extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name' => 'require|chsDash|unique:dict',
        'weight' => 'number',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '字典名称不能为空！',
        'name.chsDash' => '字典名称只能是汉字、字母、数字和下划线_及破折号-！',
        'name.unique' => '字典名称已经存在，请重新输入！',
        'weight.number' => '排序权重只能是数字！',
    ];
}
