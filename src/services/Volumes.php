<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\Volume;

class Volumes extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/volume';
    public static string $id = 'volumes';
    public static string $matchedRoute = 'volumes/volume-index';
    public static string $title = 'Volume';


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