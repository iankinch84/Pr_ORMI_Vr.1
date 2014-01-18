<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $id
 * @property integer $shoppingcart_id
 * @property string $transaction_id
 * @property string $purchasedate
 * @property integer $buyer_id
 * @property string $payer_email
 * @property string $fullname
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Shoppingcart $shoppingcart
 */
class Orders extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shoppingcart_id, transaction_id, purchasedate, buyer_id, payer_email, fullname, address, city, state, zip, country', 'required'),
			array('shoppingcart_id, buyer_id, status', 'numerical', 'integerOnly'=>true),
			array('transaction_id, purchasedate', 'length', 'max'=>20),
			array('payer_email', 'length', 'max'=>64),
			array('zip', 'length', 'max'=>16),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shoppingcart_id, transaction_id, purchasedate, buyer_id, payer_email, fullname, address, city, state, zip, country, status', 'safe', 'on'=>'search'),
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
			'shoppingcart' => array(self::BELONGS_TO, 'Shoppingcart', 'shoppingcart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'shoppingcart_id' => 'Shoppingcart',
			'transaction_id' => 'Transaction',
			'purchasedate' => 'Purchasedate',
			'buyer_id' => 'Buyer',
			'payer_email' => 'Payer Email',
			'fullname' => 'Fullname',
			'address' => 'Address',
			'city' => 'City',
			'state' => 'State',
			'zip' => 'Zip',
			'country' => 'Country',
			'status' => 'Status',
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
		$criteria->compare('shoppingcart_id',$this->shoppingcart_id);
		$criteria->compare('transaction_id',$this->transaction_id,true);
		$criteria->compare('purchasedate',$this->purchasedate,true);
		$criteria->compare('buyer_id',$this->buyer_id);
		$criteria->compare('payer_email',$this->payer_email,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('state',$this->state,true);
		$criteria->compare('zip',$this->zip,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Orders the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
