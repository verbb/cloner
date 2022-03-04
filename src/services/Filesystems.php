<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use Craft;
use craft\base\FsInterface;

class Filesystems extends Service
{
    // Properties
    // =========================================================================

    public static string $matchedRoute = 'fs/index';
    public static string $id = 'fs';
    public static string $title = 'Filesystem';
    public static string $action = 'clone/filesystem';


    // Public Methods
    // =========================================================================

    public function setupClonedFilesystem($oldFilesystem, $name, $handle): FsInterface
    {
        return Craft::$app->getFs()->createFilesystem([
            'type' => $oldFilesystem::class,
            'name' => $name,
            'handle' => $handle,
            'hasUrls' => $oldFilesystem->hasUrls,
            'url' => $oldFilesystem->url,
            'settings' => $oldFilesystem->getSettings(),
        ]);
    }

}