<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\ImageTransform;

class AssetTransforms extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/transform';
    public static string $id = 'transforms';
    public static string $matchedRoute = 'asset-transforms/transform-index';
    public static string $title = 'Asset Transform';


    // Public Methods
    // =========================================================================

    public function setupClonedTransform($oldTransform, $name, $handle): ImageTransform
    {
        $transform = new ImageTransform();
        $transform->name = $name;
        $transform->handle = $handle;

        $this->cloneAttributes($oldTransform, $transform, [
            'width',
            'height',
            'mode',
            'position',
            'quality',
            'interlace',
            'format',
        ]);

        return $transform;
    }

}