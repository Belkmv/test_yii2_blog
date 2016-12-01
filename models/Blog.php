<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "blg_blog".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $description
 * @property string $article
 * @property string $create_date
 */
class Blog extends \yii\db\ActiveRecord
{
    const DESCRIPTION_MAX_LENGHT = 255;
    const ARTICLE_MAX_LENGHT = 65000;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blg_blog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'description', 'article'], 'required'],
            [['user_id'], 'integer'],
            ['user_id', 'exist',
                'targetClass'=>User::className(),
                'targetAttribute'=> 'id'],
            [['article'], 'string', 'max' => self::ARTICLE_MAX_LENGHT],
            [['create_date'], 'safe'],
            [['description'], 'string', 'max' => self::DESCRIPTION_MAX_LENGHT],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'description' => 'Описание',
            'article' => 'Статья',
            'create_date' => 'Дата',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(),['id'=>'user_id']);
    }
    /**
     * @inheritdoc
     * @return BlogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BlogQuery(get_called_class());
    }
}
