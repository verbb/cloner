<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\elements\Entry;
use craft\helpers\StringHelper;
use craft\models\Section;
use craft\models\Section_SiteSettings;

class Sections extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/section';
    public static string $id = 'sections';
    public static string $matchedRoute = 'sections/index';
    public static string $title = 'Section';


    // Public Methods
    // =========================================================================

    public function setupClonedSection($oldSection, $newSectionName, $newSectionHandle): Section
    {
        $section = new Section();
        $section->name = $newSectionName;
        $section->handle = $newSectionHandle;

        $this->cloneAttributes($oldSection, $section, [
            'type',
            'enableVersioning',
            'propagationMethod',
            'maxLevels',
        ]);

        $allSiteSettings = [];

        foreach ($oldSection->getSiteSettings() as $siteId => $oldSiteSettings) {
            $siteSettings = new Section_SiteSettings();

            $this->cloneAttributes($oldSiteSettings, $siteSettings, [
                'sectionId',
                'siteId',
                'enabledByDefault',
                'hasUrls',
                'template',
            ]);

            // Set the new uriFormat
            $siteSettings->uriFormat = StringHelper::toKebabCase($section->name);

            if ($section->type !== Section::TYPE_SINGLE) {
                $siteSettings->uriFormat .= '/{slug}';
            }

            $allSiteSettings[$siteId] = $siteSettings;
        }

        $section->setSiteSettings($allSiteSettings);

        return $section;
    }

}