<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\elements\Tag;
use craft\models\TagGroup;

class TagGroups extends Service
{
    // Properties
    // =========================================================================

    public static $matchedRoute = 'tags/index';
    public static $id = 'taggroups';
    public static $title = 'Tag Group';
    public static $action = 'clone/tag-group';


    // Public Methods
    // =========================================================================

    public function setupClonedTagGroup($oldTagGroup, $name, $handle)
    {
        $tagGroup = new TagGroup();
        $tagGroup->name = $name;
        $tagGroup->handle = $handle;

        $fieldLayoutInfo = $this->getFieldLayout($oldTagGroup->getFieldLayout());

        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayout($fieldLayoutInfo[0], $fieldLayoutInfo[1]);
        $fieldLayout->type = Tag::class;
        $tagGroup->setFieldLayout($fieldLayout);

        return $tagGroup;
    }

}