<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Cwp\V20180228\Models;
use TencentCloud\Common\AbstractModel;

/**
 * SetLocalStorageItem请求参数结构体
 *
 * @method string getKey() 获取键
 * @method void setKey(string $Key) 设置键
 * @method string getValue() 获取值
 * @method void setValue(string $Value) 设置值
 * @method integer getExpire() 获取失效时间（单位；秒）
 * @method void setExpire(integer $Expire) 设置失效时间（单位；秒）
 */
class SetLocalStorageItemRequest extends AbstractModel
{
    /**
     * @var string 键
     */
    public $Key;

    /**
     * @var string 值
     */
    public $Value;

    /**
     * @var integer 失效时间（单位；秒）
     */
    public $Expire;

    /**
     * @param string $Key 键
     * @param string $Value 值
     * @param integer $Expire 失效时间（单位；秒）
     */
    function __construct()
    {

    }

    /**
     * For internal only. DO NOT USE IT.
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("Key",$param) and $param["Key"] !== null) {
            $this->Key = $param["Key"];
        }

        if (array_key_exists("Value",$param) and $param["Value"] !== null) {
            $this->Value = $param["Value"];
        }

        if (array_key_exists("Expire",$param) and $param["Expire"] !== null) {
            $this->Expire = $param["Expire"];
        }
    }
}
