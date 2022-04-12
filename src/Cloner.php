<?php
namespace verbb\cloner;

use verbb\cloner\assetbundles\ClonerAsset;
use verbb\cloner\base\PluginTrait;

use Craft;
use craft\base\Plugin;
use craft\helpers\Json;
use craft\web\View;

use yii\base\Event;

use Throwable;

class Cloner extends Plugin
{
    // Properties
    // =========================================================================

    public string $schemaVersion = '1.0.0';


    // Traits
    // =========================================================================

    use PluginTrait;


    // Public Methods
    // =========================================================================

    public function init(): void
    {
        parent::init();

        self::$plugin = $this;

        $this->_setPluginComponents();
        $this->_setLogging();
        $this->_registerResources();
    }

    // Private Methods
    // =========================================================================

    private function _registerResources(): void
    {
        $request = Craft::$app->getRequest();

        // Only ever trigger this for CP requests, and ignore for Ajax
        if (!$request->getIsCpRequest() || $request->getAcceptsJson()) {
            return;
        }

        Event::on(View::class, View::EVENT_END_BODY, function(Event $event) use ($request) {
            try {
                $registeredGroups = $this->getService()->getRegisteredGroups();

                $route = Craft::$app->requestedRoute;

                if ($route === 'templates/render') {
                    $route = $request->pathInfo;
                }

                // Find the matching rule-set in our settings, otherwise don't proceed
                if (!isset($registeredGroups[$route])) {
                    return;
                }

                $view = $event->sender;
                $view->registerAssetBundle(ClonerAsset::class);

                // Render our JS + CSS using the provided rule-set as per the matched route
                $view->registerJs('new Craft.Cloner(' .
                    Json::encode($registeredGroups[$route], JSON_UNESCAPED_UNICODE) .
                ');');
            } catch (Throwable $e) {
                // Ignore any errors here, usually caught by Craft. Mostly to catch fatal PHP errors properly.
            }
        });
    }
}
