<?php

/**
 * This is the model class for table "{{goods_pic}}".
 *
 * The followings are the available columns in table '{{goods_pic}}':
 * @property string $id
 * @property string $goods_info_id
 * @property string $big_pic_url
 * @property string $medium_pic_url
 * @property string $small_pic_url
 * @property integer $status
 * @property integer $_delete
 * @property string $_create_time
 * @property string $_update_time
 */
namespace application\models\Shop;
use OperationLog;
class GoodsPic extends \CActiveRecord
{
	public function tableName()
	{
		return '{{goods_pic}}';
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
			'goods_info_id' => '商品info的ID',
			'big_pic_url' => '大图',
			'medium_pic_url' => '中图',
			'small_pic_url' => '小图',
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
		$column_name = 'GoodsPic';
		if($this->isNewRecord){
			$column_name = '新增GoodsPic';
			$operate = 'add';
		}else{
			if($this->_delete == 1){
				$column_name = '删除GoodsPic';
				$operate = 'del';
			}else{
				$column_name = '编辑GoodsPic';
				$operate = 'edit';
			}
		}
		OperationLog::addLog(OperationLog::$operationGoodsPic, $operate, $column_name, $this->id, $this->old_date, $this->attributes);
		$this->old_date = '';
	}

	public function defaultScope()
	{
		return array(
		'condition' => "_delete=0",
		);
	}

	public function createGoodsPic($info){
		$model = new self();
		foreach($info as $k=>$v){
			$model->$k = $v;
		}
		$model->save();
		return $model;
	}

	public function updateGoodsPic($info){
		$this->old_date = $this->attributes;
		foreach($info as $k=>$v){
			$this->$k = $v;
		}
		$this->save();
	}

	public function deleteGoodsPic(){
		$this->_delete=1;
		return $this->save();
	}

}
