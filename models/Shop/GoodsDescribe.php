<?php

/**
 * This is the model class for table "{{goods_describe}}".
 *
 * The followings are the available columns in table '{{goods_describe}}':
 * @property string $id
 * @property string $goods_id
 * @property string $goods_params
 * @property string $goods_desc
 * @property integer $status
 * @property integer $_delete
 * @property string $_create_time
 * @property string $_update_time
 */
namespace application\models\Shop;
use OperationLog;
class GoodsDescribe extends \CActiveRecord
{
	public function tableName()
	{
		return '{{goods_describe}}';
	}

	public function rules()
	{
		return array();
	}

	public function relations()
	{
		return array();
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'id',
			'goods_id' => '商品ID',
			'goods_params' => '参数',
			'goods_desc' => '商品的详细描述',
			'status' => '状态',
			'_delete' => '是否已经删除，0 ，否； 1 ，已删除',
			'_create_time' => '添加时间',
			'_update_time' => '更新时间',
		);
	}

	public function search()
	{

	}


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public $old_date = '';

	protected function beforeSave()
	{
		if(parent::beforeSave()){
			if($this->isNewRecord){
				$this->status = 1;
				$this->_delete = 0;
				$this->_update_time = date('y-m-d H:m:s');
				$this->_create_time = date('y-m-d H:m:s');
			}else{
				if($this->old_date == $this->attributes){
					return false;
				}
				$this->_update_time = date('y-m-d H:m:s');
			}
			return true;
		}else{
			return false;
		}
	}

	protected function afterSave(){
		$column_name = 'GoodsDescribe';
		if($this->isNewRecord){
			$column_name = '新增GoodsDescribe';
			$operate = 'add';
		}else{
			if($this->_delete == 1){
				$column_name = '删除GoodsDescribe';
				$operate = 'del';
			}else{
				$column_name = '编辑GoodsDescribe';
				$operate = 'edit';
			}
		}
		OperationLog::addLog(OperationLog::$operationGoodsDescribe, $operate, $column_name, $this->id, $this->old_date, $this->attributes);
		$this->old_date = '';
	}

	public function defaultScope()
	{
		return array(
		'condition' => "_delete=0",
		);
	}

	public function createGoodsDescribe($info){
		$model = new self();
		foreach($info as $k=>$v){
			$model->$k = $v;
		}
		$model->save();
	}

	public function updateGoodsDescribe($info){
		$this->old_date = $this->attributes;
		foreach($info as $k=>$v){
			$this->$k = $v;
		}
		$this->save();
	}

	public function deleteGoodsDescribe(){
		$this->_delete=1;
		return $this->save();
	}

}
