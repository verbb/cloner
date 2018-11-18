<?php
namespace verbb\cloner;

use verbb\cloner\assetbundles\ClonerAsset;
use verbb\cloner\base\PluginTrait;

use Craft;
use craft\base\Plugin;
use craft\helpers\Json;

class Cloner extends Plugin
{
    // Public Properties
    // =========================================================================

    public $schemaVersion = '1.0.0';


    // Traits
    // =========================================================================

    use PluginTrait;


    // Public Methods
    // =========================================================================

    public function init()
    {
        parent::init();

        self::$plugin = $this;

        $this->_setPluginComponents();
        $this->_setLogging();
        $this->_registerResources();
    }

    // Private Methods
    // =========================================================================

    private function _registerResources()
    {
        $request = Craft::$app->getRequest();

        // Only ever trigger this for CP requests
        if ($request->getIsCpRequest()) {
            $registeredGroups = $this->getService()->getRegisteredGroups();

            $result = Craft::$app->getUrlManager()->parseRequest($request);
            list($route, $params) = $result;

            if ($route === 'templates/render') {
                $route = $request->pathInfo;
            }

            // var_dump($route);

            // Find the matching rule-set in our settings, otherwise don't proceed
            if (!isset($registeredGroups[$route])) {
                return;
            }

            $view = Craft::$app->getView();
            $view->registerAssetBundle(ClonerAsset::class);

            // Render our JS + CSS using the provided rule-set as per the matched route
            $view->registerJs('new Craft.Cloner(' .
                Json::encode($registeredGroups[$route], JSON_UNESCAPED_UNICODE) .
            ');');
        }
    }
}
