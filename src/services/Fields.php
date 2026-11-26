<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use Craft;
use craft\base\FieldInterface;
use craft\db\Query;
use craft\db\Table;
use craft\fields\ContentBlock;
use craft\helpers\StringHelper;

class Fields extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/field';
    public static string $id = 'fields';
    public static string $matchedRoute = 'settings/fields';
    public static string $title = 'Field';


    // Public Methods
    // =========================================================================

    public function canCloneField(FieldInterface $field): bool
    {
        // Content Block layouts are copied by `setupClonedField()`.
        if ($field instanceof ContentBlock) {
            return true;
        }

        // Neo keeps its block types outside the field settings, so a copy would have none. Compared by name as Neo is optional.
        if (get_class($field) === 'benf\neo\Field') {
            return false;
        }

        // Other fields that own field layouts reference them by UID. A copy would share those layouts with the source.
        $uids = [];
        $this->_collectUuids($field->getSettings(), $uids);

        if (!$uids) {
            return true;
        }

        return !(new Query())
            ->from(Table::FIELDLAYOUTS)
            ->where(['uid' => array_keys($uids), 'dateDeleted' => null])
            ->exists();
    }

    public function setupClonedField(FieldInterface $oldField, string $name, string $handle): FieldInterface
    {
        $settings = $oldField->getSettings();

        if ($oldField instanceof ContentBlock) {
            $fieldLayout = $this->getFieldLayout($oldField->getFieldLayout());
            $settings['fieldLayouts'] = [StringHelper::UUID() => $fieldLayout->getConfig() ?? []];
        }

        // Build from the source's settings rather than cloning the object, so cached field state and identity stay behind.
        return Craft::$app->getFields()->createField([
            'type' => get_class($oldField),
            'name' => $name,
            'handle' => $handle,
            'instructions' => $oldField->instructions,
            'tip' => $oldField->tip,
            'warning' => $oldField->warning,
            'searchable' => $oldField->searchable,
            'translationMethod' => $oldField->translationMethod,
            'translationKeyFormat' => $oldField->translationKeyFormat,
            'settings' => $settings,
        ]);
    }


    // Private Methods
    // =========================================================================

    private function _collectUuids(array $values, array &$uids): void
    {
        foreach ($values as $value) {
            if (is_array($value)) {
                $this->_collectUuids($value, $uids);
            } elseif (is_string($value) && StringHelper::isUUID($value)) {
                $uids[$value] = true;
            }
        }
    }

}
