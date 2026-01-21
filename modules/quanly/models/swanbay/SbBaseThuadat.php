<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_base_thuadat".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property string|null $sothua
 * @property string|null $soto
 * @property string|null $chusohuu
 * @property string|null $quyhoach
 */
class SbBaseThuadat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_base_thuadat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'objectid'], 'default', 'value' => null],
            [['id', 'objectid'], 'integer'],
            [['geom'], 'string'],
            [['shape_leng', 'shape_area'], 'number'],
            [['sothua'], 'string', 'max' => 25],
            [['soto'], 'string', 'max' => 5],
            [['chusohuu'], 'string', 'max' => 100],
            [['quyhoach'], 'string', 'max' => 50],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'geom' => Yii::t('app', 'Geom'),
            'objectid' => Yii::t('app', 'Objectid'),
            'shape_leng' => Yii::t('app', 'Shape Leng'),
            'shape_area' => Yii::t('app', 'Shape Area'),
            'sothua' => Yii::t('app', 'Sothua'),
            'soto' => Yii::t('app', 'Soto'),
            'chusohuu' => Yii::t('app', 'Chusohuu'),
            'quyhoach' => Yii::t('app', 'Quyhoach'),
        ];
    }
}
