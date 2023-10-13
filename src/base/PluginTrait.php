<?php
namespace verbb\cloner\base;

use verbb\cloner\Cloner;
use verbb\cloner\services\ImageTransforms;
use verbb\cloner\services\CategoryGroups;
use verbb\cloner\services\EntryTypes;
use verbb\cloner\services\Filesystems;
use verbb\cloner\services\GlobalSets;
use verbb\cloner\services\Sections;
use verbb\cloner\services\Sites;
use verbb\cloner\services\TagGroups;
use verbb\cloner\services\UserGroups;
use verbb\cloner\services\Volumes;

use verbb\base\LogTrait;
use verbb\base\helpers\Plugin;

trait PluginTrait
{
    // Properties
    // =========================================================================

    public static ?Cloner $plugin = null;


    // Traits
    // =========================================================================

    use LogTrait;
    

    // Static Methods
    // =========================================================================

    public static function config(): array
    {
        Plugin::bootstrapPlugin('cloner');

        return [
            'components' => [
                'imageTransforms' => ImageTransforms::class,
                'categoryGroups' => CategoryGroups::class,
                'entryTypes' => EntryTypes::class,
                'filesystems' => Filesystems::class,
                'globalSets' => GlobalSets::class,
                'sections' => Sections::class,
                'service' => Service::class,
                'sites' => Sites::class,
                'tagGroups' => TagGroups::class,
                'userGroups' => UserGroups::class,
                'volumes' => Volumes::class,
            ],
        ];
    }


    // Public Methods
    // =========================================================================

    public function getImageTransforms(): ImageTransforms
    {
        return $this->get('imageTransforms');
    }

    public function getCategoryGroups(): CategoryGroups
    {
        return $this->get('categoryGroups');
    }

    public function getEntryTypes(): EntryTypes
    {
        return $this->get('entryTypes');
    }

    public function getFilesystems(): Filesystems
    {
        return $this->get('filesystems');
    }

    public function getGlobalSets(): GlobalSets
    {
        return $this->get('globalSets');
    }

    public function getEntries(): Sections
    {
        return $this->get('sections');
    }

    public function getService(): Service
    {
        return $this->get('service');
    }

    public function getSites(): Sites
    {
        return $this->get('sites');
    }

    public function getTagGroups(): TagGroups
    {
        return $this->get('tagGroups');
    }

    public function getUserGroups(): UserGroups
    {
        return $this->get('userGroups');
    }

    public function getVolumes(): Volumes
    {
        return $this->get('volumes');
    }

}