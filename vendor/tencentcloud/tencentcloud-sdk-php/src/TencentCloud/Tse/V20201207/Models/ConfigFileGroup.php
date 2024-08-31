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
namespace TencentCloud\Tse\V20201207\Models;
use TencentCloud\Common\AbstractModel;

/**
 * 配置文件组
 *
 * @method integer getId() 获取配置文件组id
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setId(integer $Id) 设置配置文件组id
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getName() 获取配置文件组名称
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setName(string $Name) 设置配置文件组名称
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getNamespace() 获取命名空间
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setNamespace(string $Namespace) 设置命名空间
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getComment() 获取备注
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setComment(string $Comment) 设置备注
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getCreateTime() 获取创建时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateTime(string $CreateTime) 设置创建时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getCreateBy() 获取创建者
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setCreateBy(string $CreateBy) 设置创建者
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getModifyTime() 获取修改时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setModifyTime(string $ModifyTime) 设置修改时间
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getModifyBy() 获取修改者
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setModifyBy(string $ModifyBy) 设置修改者
注意：此字段可能返回 null，表示取不到有效值。
 * @method integer getFileCount() 获取文件数
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setFileCount(integer $FileCount) 设置文件数
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getUserIds() 获取关联用户，link_users
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setUserIds(array $UserIds) 设置关联用户，link_users
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getGroupIds() 获取组id，link_groups
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setGroupIds(array $GroupIds) 设置组id，link_groups
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getRemoveUserIds() 获取remove_link_users
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setRemoveUserIds(array $RemoveUserIds) 设置remove_link_users
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getRemoveGroupIds() 获取remove_link_groups
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setRemoveGroupIds(array $RemoveGroupIds) 设置remove_link_groups
注意：此字段可能返回 null，表示取不到有效值。
 * @method boolean getEditable() 获取是否可编辑
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setEditable(boolean $Editable) 设置是否可编辑
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getOwner() 获取归属者
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setOwner(string $Owner) 设置归属者
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getDepartment() 获取部门
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setDepartment(string $Department) 设置部门
注意：此字段可能返回 null，表示取不到有效值。
 * @method string getBusiness() 获取业务
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setBusiness(string $Business) 设置业务
注意：此字段可能返回 null，表示取不到有效值。
 * @method array getConfigFileGroupTags() 获取配置文件组标签
注意：此字段可能返回 null，表示取不到有效值。
 * @method void setConfigFileGroupTags(array $ConfigFileGroupTags) 设置配置文件组标签
注意：此字段可能返回 null，表示取不到有效值。
 */
class ConfigFileGroup extends AbstractModel
{
    /**
     * @var integer 配置文件组id
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Id;

    /**
     * @var string 配置文件组名称
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Name;

    /**
     * @var string 命名空间
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Namespace;

    /**
     * @var string 备注
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Comment;

    /**
     * @var string 创建时间
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateTime;

    /**
     * @var string 创建者
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $CreateBy;

    /**
     * @var string 修改时间
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ModifyTime;

    /**
     * @var string 修改者
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ModifyBy;

    /**
     * @var integer 文件数
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $FileCount;

    /**
     * @var array 关联用户，link_users
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $UserIds;

    /**
     * @var array 组id，link_groups
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $GroupIds;

    /**
     * @var array remove_link_users
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $RemoveUserIds;

    /**
     * @var array remove_link_groups
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $RemoveGroupIds;

    /**
     * @var boolean 是否可编辑
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Editable;

    /**
     * @var string 归属者
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Owner;

    /**
     * @var string 部门
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Department;

    /**
     * @var string 业务
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $Business;

    /**
     * @var array 配置文件组标签
注意：此字段可能返回 null，表示取不到有效值。
     */
    public $ConfigFileGroupTags;

    /**
     * @param integer $Id 配置文件组id
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Name 配置文件组名称
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Namespace 命名空间
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Comment 备注
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $CreateTime 创建时间
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $CreateBy 创建者
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ModifyTime 修改时间
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $ModifyBy 修改者
注意：此字段可能返回 null，表示取不到有效值。
     * @param integer $FileCount 文件数
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $UserIds 关联用户，link_users
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $GroupIds 组id，link_groups
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $RemoveUserIds remove_link_users
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $RemoveGroupIds remove_link_groups
注意：此字段可能返回 null，表示取不到有效值。
     * @param boolean $Editable 是否可编辑
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Owner 归属者
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Department 部门
注意：此字段可能返回 null，表示取不到有效值。
     * @param string $Business 业务
注意：此字段可能返回 null，表示取不到有效值。
     * @param array $ConfigFileGroupTags 配置文件组标签
注意：此字段可能返回 null，表示取不到有效值。
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
        if (array_key_exists("Id",$param) and $param["Id"] !== null) {
            $this->Id = $param["Id"];
        }

        if (array_key_exists("Name",$param) and $param["Name"] !== null) {
            $this->Name = $param["Name"];
        }

        if (array_key_exists("Namespace",$param) and $param["Namespace"] !== null) {
            $this->Namespace = $param["Namespace"];
        }

        if (array_key_exists("Comment",$param) and $param["Comment"] !== null) {
            $this->Comment = $param["Comment"];
        }

        if (array_key_exists("CreateTime",$param) and $param["CreateTime"] !== null) {
            $this->CreateTime = $param["CreateTime"];
        }

        if (array_key_exists("CreateBy",$param) and $param["CreateBy"] !== null) {
            $this->CreateBy = $param["CreateBy"];
        }

        if (array_key_exists("ModifyTime",$param) and $param["ModifyTime"] !== null) {
            $this->ModifyTime = $param["ModifyTime"];
        }

        if (array_key_exists("ModifyBy",$param) and $param["ModifyBy"] !== null) {
            $this->ModifyBy = $param["ModifyBy"];
        }

        if (array_key_exists("FileCount",$param) and $param["FileCount"] !== null) {
            $this->FileCount = $param["FileCount"];
        }

        if (array_key_exists("UserIds",$param) and $param["UserIds"] !== null) {
            $this->UserIds = $param["UserIds"];
        }

        if (array_key_exists("GroupIds",$param) and $param["GroupIds"] !== null) {
            $this->GroupIds = $param["GroupIds"];
        }

        if (array_key_exists("RemoveUserIds",$param) and $param["RemoveUserIds"] !== null) {
            $this->RemoveUserIds = $param["RemoveUserIds"];
        }

        if (array_key_exists("RemoveGroupIds",$param) and $param["RemoveGroupIds"] !== null) {
            $this->RemoveGroupIds = $param["RemoveGroupIds"];
        }

        if (array_key_exists("Editable",$param) and $param["Editable"] !== null) {
            $this->Editable = $param["Editable"];
        }

        if (array_key_exists("Owner",$param) and $param["Owner"] !== null) {
            $this->Owner = $param["Owner"];
        }

        if (array_key_exists("Department",$param) and $param["Department"] !== null) {
            $this->Department = $param["Department"];
        }

        if (array_key_exists("Business",$param) and $param["Business"] !== null) {
            $this->Business = $param["Business"];
        }

        if (array_key_exists("ConfigFileGroupTags",$param) and $param["ConfigFileGroupTags"] !== null) {
            $this->ConfigFileGroupTags = [];
            foreach ($param["ConfigFileGroupTags"] as $key => $value){
                $obj = new ConfigFileGroupTag();
                $obj->deserialize($value);
                array_push($this->ConfigFileGroupTags, $obj);
            }
        }
    }
}
