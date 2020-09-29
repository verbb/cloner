<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\base\Component;
use craft\element\Entry;
use craft\helpers\StringHelper;
use craft\models\EntryType;
use craft\models\Section;
use craft\models\Section_SiteSettings;

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
    public static $matchedRoute = 'sections/entry-types-index';
    public static $id = 'entrytypes';
    public static $title = 'Entry Type';
    public static $action = 'clone/entry-type';


    // Public Methods
    // =========================================================================

    public function setupClonedEntryType($oldEntryType, $newEntryName, $newEntryHandle)
    {
        $entryType = new EntryType();
        $entryType->name = $newEntryName;
        $entryType->handle = $newEntryHandle;

        $this->cloneAttributes($oldEntryType, $entryType, [
            'sectionId',
        ]);

        $fieldLayoutInfo = $this->getFieldLayout($oldEntryType->getFieldLayout());

        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayout($fieldLayoutInfo[0], $fieldLayoutInfo[1]);
        $fieldLayout->type = Entry::class;
        $entryType->setFieldLayout($fieldLayout);

        return $entryType;
    }

    public function setupDefaultEntryType($oldEntryType, $entryType)
    {
        $this->cloneAttributes($oldEntryType, $entryType, [
            'hasTitleField',
            'titleFormat',
        ]);

        $fieldLayoutInfo = $this->getFieldLayout($oldEntryType->getFieldLayout());

        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayout($fieldLayoutInfo[0], $fieldLayoutInfo[1]);
        $fieldLayout->type = Entry::class;
        $entryType->setFieldLayout($fieldLayout);

        return $entryType;
    }

}