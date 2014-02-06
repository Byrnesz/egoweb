<?php

class User extends CActiveRecord
{
    /**
     * The followings are the available columns in table 'tbl_user':
     * @var integer $id
     * @var string $email
     * @var string $password
     * @var string $name
     * @var string $last_activity
     */

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */

    public $confirm;

    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }


    // ... other attributes

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('password', 'compare', 'compareAttribute'=>'confirm'),
            array('email, password, confirm, name, permissions', 'required','on'=>'insert'),
            array('email, name', 'unique'),
            array('email, password, confirm, permissions', 'length', 'max'=>128),
			array('lastActivity','default',
				'value'=>new CDbExpression('NOW()'),
				'setOnEmpty'=>false,'on'=>'insert'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'name' => 'Name',
            'lastActivity' => 'Last Activity',
        );
    }

    /**
     * Checks if the given password is correct.
     * @param string the password to be validated
     * @return boolean whether the password is valid
     */
    public function validatePassword($pass)
    {
        $salt=preg_split('/:/',$this->password);
        return $this->hashPassword($pass,$salt[1])===$salt[0];
    }

    /**
     * Generates the password hash.
     * @param string password
     * @param string salt
     * @return string hash
     */
    public function hashPassword($password,$salt = '')
    {
        return md5($salt.$password);
    }

    /**
     * Generates a salt that can be used to generate a password hash.
     * @return string the salt
     */
    public function generateSalt($max=5)
    {
        $characterList="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
        $i=0;
        $salt="";
        while($i<$max){
            $salt.=$characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
        // return uniqid('',true);
    }

    public function getName($member_id){
        $member = User::model()->findByPk($member_id);
        if($member)
            return $member->name;
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name);
        $criteria->compare('lastActivity',$this->last_activity);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

}