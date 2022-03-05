<?php
namespace verbb\cloner\base;

use verbb\cloner\events\RegisterClonerGroupEvent;
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

use craft\base\Component;
use craft\models\FieldLayout;

class Service extends Component
{
    // Constants
    // =========================================================================

    const EVENT_REGISTER_CLONER_GROUPS = 'registerClonerGroups';


    // Public Methods
    // =========================================================================

    public function getRegisteredGroups(): array
    {
        $groups = [];

        $registeredClasses = [
            ImageTransforms::class,
            CategoryGroups::class,
            EntryTypes::class,
            Filesystems::class,
            GlobalSets::class,
            Sections::class,
            Sites::class,
            TagGroups::class,
            UserGroups::class,
            Volumes::class,
        ];

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

    public function cloneAttributes($oldModel, $newModel, $attributes): void
    {
        foreach ($attributes as $attr) {
            $newModel->$attr = $oldModel->$attr;
        }
    }

    public function getFieldLayout($oldFieldLayout): FieldLayout
    {
        return FieldLayout::createFromConfig($oldFieldLayout->getConfig());
    }


}