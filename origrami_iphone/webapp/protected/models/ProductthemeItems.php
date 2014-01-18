<?php

/**
 * This is the model class for table "producttheme_items".
 *
 * The followings are the available columns in table 'producttheme_items':
 * @property integer $id
 * @property integer $themeid
 * @property string $url
 * @property string $bgcolor
 * @property integer $sortid
 */
class ProductthemeItems extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'producttheme_items';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('themeid, url', 'required'),
			array('themeid, sortid', 'numerical', 'integerOnly'=>true),
			array('url', 'length', 'max'=>100),
			array('bgcolor', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, themeid, url, bgcolor, sortid', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'themeid' => 'Themeid',
			'url' => 'Url',
			'bgcolor' => 'Bgcolor',
			'sortid' => 'Sortid',
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
		$criteria->compare('themeid',$this->themeid);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('bgcolor',$this->bgcolor,true);
		$criteria->compare('sortid',$this->sortid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductthemeItems the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
