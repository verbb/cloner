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

    public function setupClonedSite(Site $oldSite, string $name, string $handle): Site
    {
        $site = clone $oldSite;
        $site->id = null;
        $site->uid = null;
        $site->primary = false;
        $site->dateCreated = null;
        $site->dateUpdated = null;
        $site->name = $name;
        $site->handle = $handle;

        return $site;
    }

}
