<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\helpers\StringHelper;
use craft\models\CategoryGroup;
use craft\models\CategoryGroup_SiteSettings;

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

    public function setupClonedCategoryGroup($oldCategoryGroup, $name, $handle): CategoryGroup
    {
        $categoryGroup = new CategoryGroup();
        $categoryGroup->name = $name;
        $categoryGroup->handle = $handle;

        $this->cloneAttributes($oldCategoryGroup, $categoryGroup, [
            'maxLevels',
            'defaultPlacement',
        ]);

        $allSiteSettings = [];

        foreach ($oldCategoryGroup->getSiteSettings() as $siteId => $oldSiteSettings) {
            $siteSettings = new CategoryGroup_SiteSettings();

            $this->cloneAttributes($oldSiteSettings, $siteSettings, [
                'groupId',
                'siteId',
                'hasUrls',
                'template',
            ]);

            // Set the new uriFormat
            $siteSettings->uriFormat = StringHelper::toKebabCase($categoryGroup->name) . '/{slug}';

            $allSiteSettings[$siteId] = $siteSettings;
        }

        $categoryGroup->setSiteSettings($allSiteSettings);

        // Set the field layout
        $fieldLayout = $this->getFieldLayout($oldCategoryGroup->getFieldLayout());
        $categoryGroup->setFieldLayout($fieldLayout);

        return $categoryGroup;
    }

}
