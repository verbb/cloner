<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\helpers\StringHelper;
use craft\models\Section;

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

    public function setupClonedSection(Section $oldSection, string $newSectionName, string $newSectionHandle): Section
    {
        $section = clone $oldSection;
        $section->id = null;
        $section->uid = null;
        $section->structureId = null;
        $section->name = $newSectionName;
        $section->handle = $newSectionHandle;
        $section->setEntryTypes($oldSection->getEntryTypes());

        $allSiteSettings = [];

        foreach ($oldSection->getSiteSettings() as $siteId => $oldSiteSettings) {
            $siteSettings = clone $oldSiteSettings;
            $siteSettings->id = null;
            $siteSettings->sectionId = null;

            // Single URIs must remain unique, while other section types can preserve their full per-site formats.
            if ($section->type !== Section::TYPE_SINGLE) {
                $siteSettings->uriFormat = $oldSiteSettings->uriFormat;
            } elseif ($siteSettings->hasUrls) {
                $oldUriFormat = trim((string)$oldSiteSettings->uriFormat, '/');
                $newUriSuffix = StringHelper::toKebabCase($newSectionHandle);
                $siteSettings->uriFormat = $oldUriFormat && $oldUriFormat !== '__home__' ? "$oldUriFormat-$newUriSuffix" : $newUriSuffix;
            }

            $allSiteSettings[$siteId] = $siteSettings;
        }

        $section->setSiteSettings($allSiteSettings);

        return $section;
    }

}
