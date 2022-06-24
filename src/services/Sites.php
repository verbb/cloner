<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use craft\models\Site;

class Sites extends Service
{
    // Properties
    // =========================================================================

    public static $matchedRoute = 'sites/settings-index';
    public static $id = 'sites';
    public static $title = 'site';
    public static $action = 'clone/site';


    // Public Methods
    // =========================================================================

    public function setupClonedSite($oldSite, $name, $handle): Site
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