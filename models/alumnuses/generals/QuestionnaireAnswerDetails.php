<?php

namespace app\models\alumnuses\generals;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\identities\Users;

/**
 * This is the model class for table "questionnaire_answer_details".
 *
 * @property int $id
 * @property int|null $questionnaire_answer_id
 * @property int|null $questionnaire_detail_id
 * @property string|null $questionnaire_content_str
 * @property int|null $questionnaire_answer_value_id
 * @property string|null $questionnaire_answer_value_str
 * @property int|null $answered_at
 * @property string|null $questionnaire_answer_type_str
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 *
 * @property Users $createdBy
 * @property Users $deletedBy
 * @property QuestionnaireAnswers $questionnaireAnswer
 * @property QuestionnaireDetails $questionnaireDetail
 * @property Users $updatedBy
 */
class QuestionnaireAnswerDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questionnaire_answer_details';
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
            [['questionnaire_answer_id', 'questionnaire_detail_id', 'questionnaire_answer_value_id', 'answered_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['questionnaire_content_str', 'questionnaire_answer_value_str'], 'string'],
            [['questionnaire_answer_type_str'], 'string', 'max' => 255],
            ['created_by', 'default', 'value'=>Yii::$app->user->id],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['deleted_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['deleted_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['updated_by' => 'id']],
            [['questionnaire_answer_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionnaireAnswers::className(), 'targetAttribute' => ['questionnaire_answer_id' => 'id']],
            [['questionnaire_detail_id'], 'exist', 'skipOnError' => true, 'targetClass' => QuestionnaireDetails::className(), 'targetAttribute' => ['questionnaire_detail_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'questionnaire_answer_id' => Yii::t('app', 'Questionnaire Answer ID'),
            'questionnaire_detail_id' => Yii::t('app', 'Questionnaire Detail ID'),
            'questionnaire_content_str' => Yii::t('app', 'Questionnaire Content Str'),
            'questionnaire_answer_value_id' => Yii::t('app', 'Questionnaire Answer Value ID'),
            'questionnaire_answer_value_str' => Yii::t('app', 'Questionnaire Answer Value Str'),
            'answered_at' => Yii::t('app', 'Answered At'),
            'questionnaire_answer_type_str' => Yii::t('app', 'Questionnaire Answer Type Str'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'updated_by' => Yii::t('app', 'Updated By'),
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
     * Gets query for [[QuestionnaireAnswer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireAnswer()
    {
        return $this->hasOne(QuestionnaireAnswers::className(), ['id' => 'questionnaire_answer_id']);
    }

    /**
     * Gets query for [[QuestionnaireDetail]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQuestionnaireDetail()
    {
        return $this->hasOne(QuestionnaireDetails::className(), ['id' => 'questionnaire_detail_id']);
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
}