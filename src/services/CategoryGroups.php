<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\elements\Category;
use craft\helpers\StringHelper;
use craft\models\CategoryGroup;
use craft\models\CategoryGroup_SiteSettings;

class CategoryGroups extends Service
{
    // Properties
    // =========================================================================

    public static $matchedRoute = 'categories/group-index';
    public static $id = 'categorygroups';
    public static $title = 'Category Group';
    public static $action = 'clone/category-group';


    // Public Methods
    // =========================================================================

    public function setupClonedCategoryGroup($oldCategoryGroup, $name, $handle)
    {
        $categoryGroup = new CategoryGroup();
        $categoryGroup->name = $name;
        $categoryGroup->handle = $handle;

        $this->cloneAttributes($oldCategoryGroup, $categoryGroup, [
            'maxLevels',
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

        $fieldLayoutInfo = $this->getFieldLayout($oldCategoryGroup->getFieldLayout());

        // Set the field layout
        $fieldLayout = Craft::$app->getFields()->assembleLayout($fieldLayoutInfo[0], $fieldLayoutInfo[1]);
        $fieldLayout->type = Category::class;
        $categoryGroup->setFieldLayout($fieldLayout);

        return $categoryGroup;
    }

}
