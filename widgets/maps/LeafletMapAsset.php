<?php
namespace app\widgets\maps;


use yii\web\AssetBundle;
use yii\web\View;


class LeafletMapAsset extends AssetBundle
{
    public $sourcePath = '@app/widgets/maps/assets';

    public $jsOptions = ['position' => View::POS_HEAD];
    public $css = [
        'css/leaflet.css',
        'css/prunecluster.css',
        'css/leaflet.defaultextent.css',
       // 'css/leaflet.draw.css',
        'https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css',
        'https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.css'
    ];

    public $js = [
        'js/leaflet-src.js',
        'js/proj4.js',
        'js/L.Geoserver.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js',
        'https://unpkg.com/leaflet-geometryutil@0.9.2/leaflet.geometryutil.js',
        'https://unpkg.com/leaflet.locatecontrol/dist/L.Control.Locate.min.js',
//        'js/jquery.geocomplete.js',
        'js/PruneCluster.js',
     //   'js/leaflet.draw.js',
        // 'https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js',
        'https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js',
        'js/leaflet.measure.js',
        'js/simpleheat.js',
        'js/HeatLayer.js',
        'js/leaflet.heat.js',
        'js/leaflet.defaultextent.js',
        'js/leaflet-providers.js',
    ];
//    public $css = [
//        'css/MarkerCluster.css',
//        'css/MarkerCluster.Default.css'
//    ];
//
    public $depends = [
//        'app\modules\backend\assets\BackendAssets',
    ];

//    public $js = [
//        'https://unpkg.com/leaflet.markercluster@1.0.0/dist/leaflet.markercluster.js'
//    ];

//    public function init()
//    {
//        $this->sourcePath = __DIR__ . '/assets';
//        $this->js = YII_DEBUG ? ['js/leaflet.markercluster-src.js'] : ['js/leaflet.markercluster.js'];
//    }
}
