<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\helpers\StringHelper;
use craft\models\Site;

class Sites extends Service
{
    // Properties
    // =========================================================================

    public static string $matchedRoute = 'sites/settings-index';
    public static string $id = 'sites';
    public static string $title = 'site';
    public static string $action = 'clone/site';


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