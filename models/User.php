<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;



/**
 User model
 * @property integer $id;
 * @property string $username;
 * @property string $surname;
 * @property string $name;
 * @property string $password write-only password;
 * @property string $salt;
 * @property string $access-token;
 * @property string $create_date;
 **/
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'blg_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'name', 'surname','password'], 'required'],
            ['username', 'email'],

        ];
    }

    public function attributeLabels()
    {
        return[
          'id'=> _('ID'),
            'name'=> _('Имя'),
            'surname'=> _('Фамилия'),
            'password'=> _('Пароль'),
            'salt'=> _('Соль'),
            'access_token'=> _('Ключ авторизации'),
        ];
    }


    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->getIsNewRecord() && !empty($this-> password)){
                $this->salt =$this->saltGenerator();
            }
            if(!empty($this->password)){
                $this->password =$this->passWithSalt($this->password, $this->salt);
            }
            else{
                 unset($this->password);
            }
            return true;
        }
        else{
            return false;
        }

    }


    public static function saltGenerator(){
        return hash("sha512", uniqid('salt_',true));
    }

    public static function passWithSalt($password, $salt){
        return hash("sha512", $password . $salt);
    }

    public static function findIdentity($id)
    {
        return static:: findOne(['id'=>$id]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token'=>$token]);
    }



    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey()['id'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->access_token;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $this->passWithSalt($password, $this->salt);
    }

    public function setPassword($password)
    {
        $this->password = $this->passWithSalt($password, $this->saltGenerator());
    }

    public function generateAuthKey(){
        $this->access_token =Yii::$app->security->generateRandomString();
    }
}
