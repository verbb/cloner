<?php
namespace verbb\cloner\base;

use verbb\cloner\events\RegisterClonerGroupEvent;
use verbb\cloner\services\ImageTransforms;
use verbb\cloner\services\CategoryGroups;
use verbb\cloner\services\EntryTypes;
use verbb\cloner\services\EventTypes;
use verbb\cloner\services\Fields;
use verbb\cloner\services\Filesystems;
use verbb\cloner\services\GlobalSets;
use verbb\cloner\services\ListTypes;
use verbb\cloner\services\Sections;
use verbb\cloner\services\Sites;
use verbb\cloner\services\TagGroups;
use verbb\cloner\services\UserGroups;
use verbb\cloner\services\VoucherTypes;
use verbb\cloner\services\Volumes;

use Craft;
use craft\base\Component;
use craft\helpers\StringHelper;
use craft\models\FieldLayout;

class Service extends Component
{
    // Constants
    // =========================================================================

    public const EVENT_REGISTER_CLONER_GROUPS = 'registerClonerGroups';


    // Public Methods
    // =========================================================================

    public function getRegisteredGroups(): array
    {
        $groups = [];

        $registeredClasses = [
            ImageTransforms::class,
            CategoryGroups::class,
            EntryTypes::class,
            Fields::class,
            Filesystems::class,
            GlobalSets::class,
            Sections::class,
            Sites::class,
            TagGroups::class,
            UserGroups::class,
            Volumes::class,
        ];

        // Verbb plugin integrations only show their clone buttons while the plugin they clone for is enabled.
        $pluginClasses = [
            EventTypes::class,
            ListTypes::class,
            VoucherTypes::class,
        ];

        foreach ($pluginClasses as $pluginClass) {
            if (Craft::$app->getPlugins()->isPluginEnabled($pluginClass::$pluginHandle)) {
                $registeredClasses[] = $pluginClass;
            }
        }

        foreach ($registeredClasses as $registeredClass) {
            $groups[$registeredClass::$matchedRoute] = [
                'id' => $registeredClass::$id,
                'title' => $registeredClass::$title,
                'action' => $registeredClass::$action,
            ];
        }

        $event = new RegisterClonerGroupEvent([
            'groups' => $groups,
        ]);

        $this->trigger(self::EVENT_REGISTER_CLONER_GROUPS, $event);

        return $event->groups;
    }

    public function cloneAttributes($oldModel, $newModel, array $attributes): void
    {
        foreach ($attributes as $attr) {
            $newModel->$attr = $oldModel->$attr;
        }
    }

    public function getFieldLayout(FieldLayout $oldFieldLayout): FieldLayout
    {
        $config = $oldFieldLayout->getConfig() ?? [];
        $uidMap = [];

        // Layout references such as card views and thumbnails must follow their newly generated element UIDs.
        $this->_cycleUids($config, $uidMap);
        $this->_remapUidReferences($config, $uidMap);

        $fieldLayout = FieldLayout::createFromConfig($config);
        $fieldLayout->type = $oldFieldLayout->type;

        return $fieldLayout;
    }

    // Private Methods
    // =========================================================================

    private function _cycleUids(array &$config, array &$uidMap): void
    {
        if (isset($config['uid']) && is_string($config['uid']) && StringHelper::isUUID($config['uid'])) {
            $oldUid = $config['uid'];
            $config['uid'] = StringHelper::UUID();
            $uidMap[$oldUid] = $config['uid'];
        }

        foreach ($config as &$value) {
            if (is_array($value)) {
                $this->_cycleUids($value, $uidMap);
            }
        }

        unset($value);
    }

    private function _remapUidReferences(array &$config, array $uidMap): void
    {
        foreach ($config as &$value) {
            if (is_array($value)) {
                $this->_remapUidReferences($value, $uidMap);
            } elseif (is_string($value)) {
                $value = strtr($value, $uidMap);
            }
        }

        unset($value);
    }
}
