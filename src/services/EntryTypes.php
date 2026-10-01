<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\EntryType;

class EntryTypes extends Service
{
    // Properties
    // =========================================================================

    // `matchedRoute` - The Craft (or plugin) route that matches the page being rendered. Used to only
    // show the clone button on a specific page.
    // `id` => table id for DOM selector
    // `title` => the title-case thing we're cloning (shown in the prompt window)
    // `action` => the Cloner plugin controller action (without the prefix for the plugin).
    //
    public static string $action = 'clone/entry-type';
    public static string $id = 'entrytypes';
    public static string $matchedRoute = 'settings/entry-types';
    public static string $title = 'Entry Type';


    // Public Methods
    // =========================================================================

    public function setupClonedEntryType(EntryType $oldEntryType, string $newEntryName, string $newEntryHandle): EntryType
    {
        $entryType = clone $oldEntryType;
        $entryType->id = null;
        $entryType->uid = null;
        $entryType->fieldLayoutId = null;
        $entryType->name = $newEntryName;
        $entryType->handle = $newEntryHandle;

        $fieldLayout = $this->getFieldLayout($oldEntryType->getFieldLayout());
        $entryType->setFieldLayout($fieldLayout);

        return $entryType;
    }

}
