<?php
namespace verbb\cloner\base;

use verbb\cloner\Cloner;
use verbb\cloner\services\AssetTransforms;
use verbb\cloner\services\CategoryGroups;
use verbb\cloner\services\EntryTypes;
use verbb\cloner\services\Filesystems;
use verbb\cloner\services\GlobalSets;
use verbb\cloner\services\Sections;
use verbb\cloner\services\Sites;
use verbb\cloner\services\TagGroups;
use verbb\cloner\services\UserGroups;
use verbb\cloner\services\Volumes;

use Craft;

use yii\log\Logger;

use verbb\base\BaseHelper;

trait PluginTrait
{
    // Static Properties
    // =========================================================================

    public static Cloner $plugin;


    // Public Methods
    // =========================================================================

    public function getAssetTransforms(): AssetTransforms
    {
        return $this->get('assetTransforms');
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

    public function getSections(): Sections
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

    public static function log($message, $attributes = []): void
    {
        if ($attributes) {
            $message = Craft::t('cloner', $message, $attributes);
        }

        Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'cloner');
    }

    public static function error($message, $attributes = []): void
    {
        if ($attributes) {
            $message = Craft::t('cloner', $message, $attributes);
        }

        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, 'cloner');
    }


    // Private Methods
    // =========================================================================

    private function _setPluginComponents(): void
    {
        $this->setComponents([
            'assetTransforms' => AssetTransforms::class,
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
        ]);

        BaseHelper::registerModule();
    }

    private function _setLogging(): void
    {
        BaseHelper::setFileLogging('cloner');
    }

}