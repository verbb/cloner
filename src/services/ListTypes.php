<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use verbb\wishlist\models\ListType;

class ListTypes extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/list-type';
    public static string $id = 'types';
    public static string $matchedRoute = 'wishlist/list-types/list-type-index';
    public static string $pluginHandle = 'wishlist';
    public static string $title = 'List Type';


    // Public Methods
    // =========================================================================

    public function setupClonedListType(ListType $oldListType, string $name, string $handle): ListType
    {
        $listType = clone $oldListType;
        $listType->id = null;
        $listType->uid = null;
        $listType->fieldLayoutId = null;
        $listType->itemFieldLayoutId = null;
        $listType->name = $name;
        $listType->handle = $handle;

        // Saving a default list type clears the flag on every other type, so a clone must never take it from its source.
        $listType->default = false;

        $listType->getBehavior('listFieldLayout')->setFieldLayout($this->getFieldLayout($oldListType->getListFieldLayout()));
        $listType->getBehavior('itemFieldLayout')->setFieldLayout($this->getFieldLayout($oldListType->getItemFieldLayout()));

        return $listType;
    }

}
