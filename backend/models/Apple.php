<?php

namespace backend\models;

/**
 * This is the model class for table "apple".
 *
 * @property integer $id
 * @property string $color
 * @property integer $created_at
 * @property integer $fell_at
 * @property integer $eat
 */
class Apple extends \yii\db\ActiveRecord
{
    const HOURS_TO_SPOIL = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'apple';
    }
}
