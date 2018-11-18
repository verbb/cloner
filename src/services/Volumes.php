<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\elements\Asset;
use craft\helpers\StringHelper;
use craft\models\AssetTransform;
use craft\models\Volume;

class Volumes extends Service
{
    // Properties
    // =========================================================================

    public static $matchedRoute = 'volumes/volume-index';
    public static $id = 'volumes';
    public static $title = 'Volume';
    public static $action = 'clone/volume';


    // Public Methods
    // =========================================================================

    public function setupClonedVolume($oldVolume, $name, $handle)
    {
        $volume = Craft::$app->getVolumes()->createVolume([
            'type' => get_class($oldVolume),
            'name' => $name,
            'handle' => $handle,
            'hasUrls' => $oldVolume->hasUrls,
            'url' => $oldVolume->url,
            'settings' => $oldVolume->settings,
        ]);

        $fieldLayoutInfo = $this->getFieldLayout($oldVolume->getFieldLayout());

        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayout($fieldLayoutInfo[0], $fieldLayoutInfo[1]);
        $fieldLayout->type = Asset::class;
        $volume->setFieldLayout($fieldLayout);

        return $volume;
    }

}