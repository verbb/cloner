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

    public static string $matchedRoute = 'volumes/volume-index';
    public static string $id = 'volumes';
    public static string $title = 'Volume';
    public static string $action = 'clone/volume';


    // Public Methods
    // =========================================================================

    public function setupClonedVolume($oldVolume, $name, $handle): Volume
    {
        $volume = new Volume([
            'name' => $name,
            'handle' => $handle,
            'fsHandle' => $oldVolume->fsHandle,
        ]);

        // Set the field layout
        $fieldLayout = $this->getFieldLayout($oldVolume->getFieldLayout());
        $volume->setFieldLayout($fieldLayout);

        return $volume;
    }

}