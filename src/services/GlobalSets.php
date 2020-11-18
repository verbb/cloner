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

    public static $matchedRoute = 'settings/globals';
    public static $id = 'sets';
    public static $title = 'Global Set';
    public static $action = 'clone/global-set';


    // Public Methods
    // =========================================================================

    public function setupClonedGlobalSet($oldGlobalSet, $name, $handle)
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