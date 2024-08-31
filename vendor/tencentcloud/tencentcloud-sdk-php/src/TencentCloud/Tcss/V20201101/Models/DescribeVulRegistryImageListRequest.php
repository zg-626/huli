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
namespace TencentCloud\Tcss\V20201101\Models;
use TencentCloud\Common\AbstractModel;

/**
 * DescribeVulRegistryImageList请求参数结构体
 *
 * @method string getPocID() 获取漏洞ID
 * @method void setPocID(string $PocID) 设置漏洞ID
 * @method integer getLimit() 获取需要返回的数量，默认为10，最大值为100
 * @method void setLimit(integer $Limit) 设置需要返回的数量，默认为10，最大值为100
 * @method integer getOffset() 获取偏移量，默认为0。
 * @method void setOffset(integer $Offset) 设置偏移量，默认为0。
 * @method array getFilters() 获取过滤条件。
OnlyAffectedNewestImage bool 是否影响最新镜像
ImageDigest 镜像Digest，支持模糊查询
ImageId 镜像ID，支持模糊查询
Namespace 命名空间，支持模糊查询
ImageTag 镜像版本，支持模糊查询
InstanceName 实例名称，支持模糊查询
ImageName 镜像名，支持模糊查询
ImageRepoAddress 镜像地址，支持模糊查询
 * @method void setFilters(array $Filters) 设置过滤条件。
OnlyAffectedNewestImage bool 是否影响最新镜像
ImageDigest 镜像Digest，支持模糊查询
ImageId 镜像ID，支持模糊查询
Namespace 命名空间，支持模糊查询
ImageTag 镜像版本，支持模糊查询
InstanceName 实例名称，支持模糊查询
ImageName 镜像名，支持模糊查询
ImageRepoAddress 镜像地址，支持模糊查询
 * @method string getOrder() 获取排序方式
 * @method void setOrder(string $Order) 设置排序方式
 * @method string getBy() 获取排序字段
 * @method void setBy(string $By) 设置排序字段
 */
class DescribeVulRegistryImageListRequest extends AbstractModel
{
    /**
     * @var string 漏洞ID
     */
    public $PocID;

    /**
     * @var integer 需要返回的数量，默认为10，最大值为100
     */
    public $Limit;

    /**
     * @var integer 偏移量，默认为0。
     */
    public $Offset;

    /**
     * @var array 过滤条件。
OnlyAffectedNewestImage bool 是否影响最新镜像
ImageDigest 镜像Digest，支持模糊查询
ImageId 镜像ID，支持模糊查询
Namespace 命名空间，支持模糊查询
ImageTag 镜像版本，支持模糊查询
InstanceName 实例名称，支持模糊查询
ImageName 镜像名，支持模糊查询
ImageRepoAddress 镜像地址，支持模糊查询
     */
    public $Filters;

    /**
     * @var string 排序方式
     */
    public $Order;

    /**
     * @var string 排序字段
     */
    public $By;

    /**
     * @param string $PocID 漏洞ID
     * @param integer $Limit 需要返回的数量，默认为10，最大值为100
     * @param integer $Offset 偏移量，默认为0。
     * @param array $Filters 过滤条件。
OnlyAffectedNewestImage bool 是否影响最新镜像
ImageDigest 镜像Digest，支持模糊查询
ImageId 镜像ID，支持模糊查询
Namespace 命名空间，支持模糊查询
ImageTag 镜像版本，支持模糊查询
InstanceName 实例名称，支持模糊查询
ImageName 镜像名，支持模糊查询
ImageRepoAddress 镜像地址，支持模糊查询
     * @param string $Order 排序方式
     * @param string $By 排序字段
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
        if (array_key_exists("PocID",$param) and $param["PocID"] !== null) {
            $this->PocID = $param["PocID"];
        }

        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }

        if (array_key_exists("Offset",$param) and $param["Offset"] !== null) {
            $this->Offset = $param["Offset"];
        }

        if (array_key_exists("Filters",$param) and $param["Filters"] !== null) {
            $this->Filters = [];
            foreach ($param["Filters"] as $key => $value){
                $obj = new AssetFilters();
                $obj->deserialize($value);
                array_push($this->Filters, $obj);
            }
        }

        if (array_key_exists("Order",$param) and $param["Order"] !== null) {
            $this->Order = $param["Order"];
        }

        if (array_key_exists("By",$param) and $param["By"] !== null) {
            $this->By = $param["By"];
        }
    }
}
