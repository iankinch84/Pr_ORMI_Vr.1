<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $id
 * @property integer $shoppingcart_id
 * @property string $products
 * @property integer $amount
 * @property integer $type
 * @property integer $themeid
 * @property integer $border
 * @property integer $datetype
 * @property string $created
 * @property string $modified
 * @property string $pdfartwork
 * @property integer $status
 *
 * The followings are the available model relations:
 * @property Shoppingcart $shoppingcart
 * @property Producttheme $theme
 */
class Products extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('shoppingcart_id, products, type, created', 'required'),
			array('shoppingcart_id, amount, type, themeid, border, datetype, status', 'numerical', 'integerOnly'=>true),
			array('created, modified', 'length', 'max'=>20),
			array('pdfartwork', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, shoppingcart_id, products, amount, type, themeid, border, datetype, created, modified, pdfartwork, status', 'safe', 'on'=>'search'),
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
			'theme' => array(self::BELONGS_TO, 'Producttheme', 'themeid'),
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
			'products' => 'Products',
			'amount' => 'Amount',
			'type' => 'Type',
			'themeid' => 'Themeid',
			'border' => 'Border',
			'datetype' => 'Datetype',
			'created' => 'Created',
			'modified' => 'Modified',
			'pdfartwork' => 'Pdfartwork',
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
		$criteria->compare('products',$this->products,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('type',$this->type);
		$criteria->compare('themeid',$this->themeid);
		$criteria->compare('border',$this->border);
		$criteria->compare('datetype',$this->datetype);
		$criteria->compare('created',$this->created,true);
		$criteria->compare('modified',$this->modified,true);
		$criteria->compare('pdfartwork',$this->pdfartwork,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Products the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
