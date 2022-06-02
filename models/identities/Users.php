<?php

namespace app\models\identities;

use yii\behaviors\TimestampBehavior;
use Yii;
use app\models\mains\generals\Settings;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $full_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $address
 * @property string|null $password_hash
 * @property string|null $image
 * @property string|null $user_bio
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property int|null $role_id
 * @property int|null $role_str
 * @property int $status
 * @property int|null $created_at
 * @property int $created_by
 * @property int|null $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property Companies[] $companies
 * @property Companies[] $companies0
 * @property Companies[] $companies1
 * @property Companies[] $companies2
 * @property Companies[] $companies3
 * @property ProjectUsers[] $projectUsers
 * @property ProjectUsers[] $projectUsers0
 * @property ProjectUsers[] $projectUsers1
 * @property ProjectUsers[] $projectUsers2
 * @property Projects[] $projects
 * @property Projects[] $projects0
 * @property Projects[] $projects1
 * @property Projects[] $projects2
 * @property RequestDetails[] $requestDetails
 * @property RequestDetails[] $requestDetails0
 * @property RequestDetails[] $requestDetails1
 * @property Requests[] $requests
 * @property Requests[] $requests0
 * @property Requests[] $requests1
 * @property Requests[] $requests2
 * @property Welders[] $welders
 * @property Welders[] $welders0
 * @property Welders[] $welders1
 * @property-read null|string $authKey
 * @property-write string $password
 * @property Welders[] $welders2
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $new_password, $repeat_password, $old_password, $password;

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = -1;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'full_name', 'email', 'role_id'], 'required'],
            [['username', 'email'], 'unique'],
            [['address', 'user_bio'], 'string'],
            [['role_id', 'role_str', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['username', 'full_name', 'email', 'password_hash', 'image', 'auth_key', 'password_reset_token'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 13],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            ['new_password', 'string', 'min' => 6, 'on' => 'change-password'],
            [['new_password', 'repeat_password', 'old_password'], 'required', 'on' => 'change-password'],
            ['repeat_password', 'compare', 'compareAttribute'=> 'new_password', 'on'=> 'change-password'],
            ['old_password', 'oldPassword', 'on' => 'change-password'],
            // ['image', 'image', 'skipOnEmpty' => true, 'extensions' => 'jpg, png'],
            [['image'], 'safe'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['role_id' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'full_name' => 'Full Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'address' => 'Address',
            'password_hash' => 'Password Hash',
            'image' => 'Image',
            'user_bio' => 'User Bio',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
            'role_id' => 'Role ID',
            'role_str' => 'Role Str',
            'status' => 'Status',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne( ['access_token' => hash( 'sha256' , $token)]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */

    public function oldPassword($attribute, $params)
    {
        $_user     = self::findOne(Yii::$app->user->id);
        $_validate = Yii::$app->security->validatePassword($this->old_password, $_user->password_hash);
        if(!$_validate){
            $this->addError($attribute, 'Old password is wrong.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
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
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Gets query for [[Companies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies()
    {
        return $this->hasMany(Companies::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Companies0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies0()
    {
        return $this->hasMany(Companies::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Companies1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies1()
    {
        return $this->hasMany(Companies::className(), ['pic_ex_user_id' => 'id']);
    }

    /**
     * Gets query for [[Companies2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies2()
    {
        return $this->hasMany(Companies::className(), ['pic_in_user_id' => 'id']);
    }

    /**
     * Gets query for [[Companies3]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompanies3()
    {
        return $this->hasMany(Companies::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'created_by']);
    }

    /**
     * Gets query for [[DeletedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'deleted_by']);
    }

    /**
     * Gets query for [[ProjectUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers()
    {
        return $this->hasMany(ProjectUsers::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[ProjectUsers0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers0()
    {
        return $this->hasMany(ProjectUsers::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[ProjectUsers1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers1()
    {
        return $this->hasMany(ProjectUsers::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[ProjectUsers2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjectUsers2()
    {
        return $this->hasMany(ProjectUsers::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Projects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects()
    {
        return $this->hasMany(Projects::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Projects0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects0()
    {
        return $this->hasMany(Projects::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Projects1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects1()
    {
        return $this->hasMany(Projects::className(), ['pic_user_id' => 'id']);
    }

    /**
     * Gets query for [[Projects2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProjects2()
    {
        return $this->hasMany(Projects::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails()
    {
        return $this->hasMany(RequestDetails::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails0()
    {
        return $this->hasMany(RequestDetails::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[RequestDetails1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequestDetails1()
    {
        return $this->hasMany(RequestDetails::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Requests]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Requests::className(), ['company_id' => 'id']);
    }

    /**
     * Gets query for [[Requests0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests0()
    {
        return $this->hasMany(Requests::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Requests1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests1()
    {
        return $this->hasMany(Requests::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Requests2]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRequests2()
    {
        return $this->hasMany(Requests::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Settings::className(), ['id' => 'role_id']);
    }

    /**
     * Gets query for [[Settings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSettings()
    {
        return $this->hasMany(Settings::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Settings0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSettings0()
    {
        return $this->hasMany(Settings::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Settings1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSettings1()
    {
        return $this->hasMany(Settings::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_by']);
    }

    /**
     * Gets query for [[Users]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(Users::className(), ['updated_by' => 'id']);
    }

    /**
     * Gets query for [[Users0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(Users::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Users1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsers1()
    {
        return $this->hasMany(Users::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Welders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelders()
    {
        return $this->hasMany(Welders::className(), ['created_by' => 'id']);
    }

    /**
     * Gets query for [[Welders0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelders0()
    {
        return $this->hasMany(Welders::className(), ['deleted_by' => 'id']);
    }

    /**
     * Gets query for [[Welders1]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWelders1()
    {
        return $this->hasMany(Welders::className(), ['updated_by' => 'id']);
    }
}