<?php

/**
 * This is the model class for table "shoppingcart".
 *
 * The followings are the available columns in table 'shoppingcart':
 * @property integer $id
 * @property integer $instagram_id
 * @property integer $coupon
 * @property string $fbshare
 * @property string $twshare
 * @property integer $status
 * @property integer $customer_id
 *
 * The followings are the available model relations:
 * @property Orders[] $orders
 * @property Products[] $products
 */
class Shoppingcart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'shoppingcart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('customer_id', 'required'),
			array('instagram_id, coupon, status, customer_id', 'numerical', 'integerOnly'=>true),
			array('fbshare, twshare', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, instagram_id, coupon, fbshare, twshare, status, customer_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'orders' => array(self::HAS_MANY, 'Orders', 'shoppingcart_id'),
			'products' => array(self::HAS_MANY, 'Products', 'shoppingcart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'instagram_id' => 'Instagram',
			'coupon' => 'Coupon',
			'fbshare' => 'Fbshare',
			'twshare' => 'Twshare',
			'status' => 'Status',
			'customer_id' => 'Customer',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('instagram_id',$this->instagram_id);
		$criteria->compare('coupon',$this->coupon);
		$criteria->compare('fbshare',$this->fbshare,true);
		$criteria->compare('twshare',$this->twshare,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('customer_id',$this->customer_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Shoppingcart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
