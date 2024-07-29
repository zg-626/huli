<?php

declare (strict_types = 1);

namespace app\mxadmin\validate;

use think\Validate;

class DictData extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'name' => 'require|chsAlphaNum|unique:dict_data',
        'weight' => 'number',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'name.require' => '字典项名称不能为空！',
        'name.chsAlphaNum' => '字典项名称只能是汉字、字母和数字！',
        'name.unique' => '字典项名称已经存在，请重新输入！',
        'weight.number' => '排序权重只能是数字！',
    ];
}
