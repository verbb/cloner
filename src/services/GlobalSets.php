<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\elements\GlobalSet;

class GlobalSets extends Service
{
    // Properties
    // =========================================================================

    public static string $matchedRoute = 'settings/globals';
    public static string $id = 'sets';
    public static string $title = 'Global Set';
    public static string $action = 'clone/global-set';


    // Public Methods
    // =========================================================================

    public function setupClonedGlobalSet($oldGlobalSet, $name, $handle): GlobalSet
    {
        $globalSet = new GlobalSet();
        $globalSet->name = $name;
        $globalSet->handle = $handle;

        // Set the field layout
        $fieldLayout = $this->getFieldLayout($oldGlobalSet->getFieldLayout());
        $globalSet->setFieldLayout($fieldLayout);

        return $globalSet;
    }

}