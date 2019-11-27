<?php
namespace verbb\cloner\base;

use verbb\cloner\Cloner;
use verbb\cloner\services\AssetTransforms;
use verbb\cloner\services\CategoryGroups;
use verbb\cloner\services\EntryTypes;
use verbb\cloner\services\GlobalSets;
use verbb\cloner\services\Sections;
use verbb\cloner\services\Sites;
use verbb\cloner\services\TagGroups;
use verbb\cloner\services\UserGroups;
use verbb\cloner\services\Volumes;

use Craft;
use craft\log\FileTarget;

use yii\log\Logger;

trait PluginTrait
{
    // Static Properties
    // =========================================================================

    public static $plugin;


    // Public Methods
    // =========================================================================

    public function getAssetTransforms()
    {
        return $this->get('assetTransforms');
    }

    public function getCategoryGroups()
    {
        return $this->get('categoryGroups');
    }

    public function getEntryTypes()
    {
        return $this->get('entryTypes');
    }

    public function getGlobalSets()
    {
        return $this->get('globalSets');
    }

    public function getSections()
    {
        return $this->get('sections');
    }

    public function getService()
    {
        return $this->get('service');
    }

    public function getSites()
    {
        return $this->get('sites');
    }

    public function getTagGroups()
    {
        return $this->get('tagGroups');
    }

    public function getUserGroups()
    {
        return $this->get('userGroups');
    }

    public function getVolumes()
    {
        return $this->get('volumes');
    }

    private function _setPluginComponents()
    {
        $this->setComponents([
            'assetTransforms' => AssetTransforms::class,
            'categoryGroups' => CategoryGroups::class,
            'entryTypes' => EntryTypes::class,
            'globalSets' => GlobalSets::class,
            'sections' => Sections::class,
            'service' => Service::class,
            'sites' => Sites::class,
            'tagGroups' => TagGroups::class,
            'userGroups' => UserGroups::class,
            'volumes' => Volumes::class,
        ]);
    }

    private function _setLogging()
    {
        Craft::getLogger()->dispatcher->targets[] = new FileTarget([
            'logFile' => Craft::getAlias('@storage/logs/cloner.log'),
            'categories' => ['cloner'],
        ]);
    }

    public static function log($message, $attributes = [])
    {
        if ($attributes) {
            $message = Craft::t('cloner', $message, $attributes);
        }

        Craft::getLogger()->log($message, Logger::LEVEL_INFO, 'cloner');
    }

    public static function error($message, $attributes = [])
    {
        if ($attributes) {
            $message = Craft::t('cloner', $message, $attributes);
        }

        Craft::getLogger()->log($message, Logger::LEVEL_ERROR, 'cloner');
    }

}