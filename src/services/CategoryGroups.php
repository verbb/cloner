<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\CategoryGroup;

class CategoryGroups extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/category-group';
    public static string $id = 'categorygroups';
    public static string $matchedRoute = 'categories/group-index';
    public static string $title = 'Category Group';


    // Public Methods
    // =========================================================================

    public function setupClonedCategoryGroup(CategoryGroup $oldCategoryGroup, string $name, string $handle): CategoryGroup
    {
        $categoryGroup = clone $oldCategoryGroup;
        $categoryGroup->id = null;
        $categoryGroup->uid = null;
        $categoryGroup->fieldLayoutId = null;
        $categoryGroup->structureId = null;
        $categoryGroup->dateDeleted = null;
        $categoryGroup->name = $name;
        $categoryGroup->handle = $handle;

        $allSiteSettings = [];

        foreach ($oldCategoryGroup->getSiteSettings() as $siteId => $oldSiteSettings) {
            $siteSettings = clone $oldSiteSettings;
            $siteSettings->id = null;
            $siteSettings->groupId = null;

            $allSiteSettings[$siteId] = $siteSettings;
        }

        $categoryGroup->setSiteSettings($allSiteSettings);

        $fieldLayout = $this->getFieldLayout($oldCategoryGroup->getFieldLayout());
        $categoryGroup->setFieldLayout($fieldLayout);

        return $categoryGroup;
    }

}
