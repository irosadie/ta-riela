<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;

/**
 * This is the model class for table "questionnaire_details".
 *
 * @property int $id
 * @property int|null $questionnaire_id
 * @property int|null $content
 * @property string|null $answer_type
 * @property string|null $answer_type_str
 * @property string|null $option_values
 * @property string|null $default_value
 * @property int|null $group_id
 * @property int|null $queue_of_parent
 * @property int|null $queue_of_group
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property Settings $group
 * @property Questionnaires $questionnaire
 * @property QuestionnaireAnswerDetails[] $questionnaireAnswerDetails
 * @property QuestionnaireAnswers[] $questionnaireAnswers
 * @property Users $updatedAt
 */
class QuestionnaireDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questionnaire_details';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['questionnaire_id', 'content', 'group_id', 'queue_of_parent', 'queue_of_group', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['answer_type', 'option_values'], 'string'],
            [['answer_type_str', 'default_value'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_at'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_at' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Settings::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['questionnaire_id'], 'exist', 'skipOnError' => true, 'targetClass' => Questionnaires::className(), 'targetAttribute' => ['questionnaire_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'questionnaire_id' => Yii::t('app', 'Questionnaire ID'),
            'content' => Yii::t('app', 'Content'),
            'answer_type' => Yii::t('app', 'Answer Type'),
            'answer_type_str' => Yii::t('app', 'Answer Type Str'),
            'option_values' => Yii::t('app', 'Option Values'),
            'default_value' => Yii::t('app', 'Default Value'),
            'group_id' => Yii::t('app', 'Group ID'),
            'queue_of_parent' => Yii::t('app', 'Queue Of Parent'),
            'queue_of_group' => Yii::t('app', 'Queue Of Group'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'deleted_at' => Yii::t('app', 'Deleted At'),
            'deleted_by' => Yii::t('app', 'Deleted By'),
        ];
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
     * Gets query for [[Group]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(Settings::className(), ['id' => 'group_id']);
    }

    /**
     * Gets query for [[Questionnaire]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaire()
    {
        return $this->hasOne(Questionnaires::className(), ['id' => 'questionnaire_id']);
    }

    /**
     * Gets query for [[QuestionnaireAnswerDetails]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireAnswerDetails()
    {
        return $this->hasMany(QuestionnaireAnswerDetails::className(), ['questionnaire_detail_id' => 'id']);
    }

    /**
     * Gets query for [[QuestionnaireAnswers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireAnswers()
    {
        return $this->hasMany(QuestionnaireAnswers::className(), ['questionnaire_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedAt]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedAt()
    {
        return $this->hasOne(Users::className(), ['id' => 'updated_at']);
    }
}