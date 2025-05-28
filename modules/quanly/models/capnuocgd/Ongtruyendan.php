<?php

namespace app\modules\quanly\models\capnuocgd;
use app\modules\quanly\base\QuanlyBaseModel;

use Yii;

/**
 * This is the model class for table "v2_4326_ONGTRUYENDAN".
 *
 * @property int $id
 * @property string|null $geom
 * @property string|null $vatlieu
 * @property int|null $coong
 * @property int|null $namlapdat
 * @property string|null $tencongtri
 * @property string|null $donvithiet
 * @property string|null $donvithico
 * @property string|null $geojson
 */
class Ongtruyendan extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v2_4326_ONGTRUYENDAN';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom', 'geojson'], 'string'],
            [['coong', 'namlapdat'], 'default', 'value' => null],
            [['coong', 'namlapdat'], 'integer'],
            [['vatlieu'], 'string', 'max' => 50],
            [['tencongtri'], 'string', 'max' => 250],
            [['donvithiet', 'donvithico'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'geom' => 'Geom',
            'vatlieu' => 'Vatlieu',
            'coong' => 'Coong',
            'namlapdat' => 'Namlapdat',
            'tencongtri' => 'Tencongtri',
            'donvithiet' => 'Donvithiet',
            'donvithico' => 'Donvithico',
            'geojson' => 'Geojson',
        ];
    }
}
