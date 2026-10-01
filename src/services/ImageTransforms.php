<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\ImageTransform;

class ImageTransforms extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/transform';
    public static string $id = 'transforms';
    public static string $matchedRoute = 'image-transforms/index';
    public static string $title = 'Asset Transform';


    // Public Methods
    // =========================================================================

    public function setupClonedTransform(ImageTransform $oldTransform, string $name, string $handle): ImageTransform
    {
        $transform = clone $oldTransform;
        $transform->id = null;
        $transform->uid = null;
        $transform->name = $name;
        $transform->handle = $handle;

        return $transform;
    }

}
