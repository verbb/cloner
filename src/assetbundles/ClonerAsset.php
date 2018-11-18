<?php
namespace verbb\cloner\assetbundles;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

class ClonerAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    public function init()
    {
        $this->sourcePath = "@verbb/cloner/resources/dist";

        $this->depends = [
            CpAsset::class,
        ];

        $this->css = [
            'css/cloner.css',
        ];

        $this->js = [
            'js/cloner.js',
        ];

        parent::init();
    }
}
