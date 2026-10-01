<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\TagGroup;

class TagGroups extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/tag-group';
    public static string $id = 'taggroups';
    public static string $matchedRoute = 'tags/index';
    public static string $title = 'Tag Group';


    // Public Methods
    // =========================================================================

    public function setupClonedTagGroup(TagGroup $oldTagGroup, string $name, string $handle): TagGroup
    {
        $tagGroup = clone $oldTagGroup;
        $tagGroup->id = null;
        $tagGroup->uid = null;
        $tagGroup->fieldLayoutId = null;
        $tagGroup->dateDeleted = null;
        $tagGroup->name = $name;
        $tagGroup->handle = $handle;

        $fieldLayout = $this->getFieldLayout($oldTagGroup->getFieldLayout());
        $tagGroup->setFieldLayout($fieldLayout);

        return $tagGroup;
    }

}
