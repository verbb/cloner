<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\Site;

class Sites extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/site';
    public static string $id = 'sites';
    public static string $matchedRoute = 'sites/settings-index';
    public static string $title = 'site';


    // Public Methods
    // =========================================================================

    public function setupClonedSite($oldSite, string $name, $handle): Site
    {
        $site = new Site();
        $site->name = $name;
        $site->handle = $handle;

        $this->cloneAttributes($oldSite, $site, [
            'groupId',
            'language',
            'hasUrls',
            'baseUrl',
        ]);

        return $site;
    }

}