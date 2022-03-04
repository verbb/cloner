<?php
namespace verbb\cloner\services;

use verbb\cloner\base\Service;

use Craft;
use craft\models\UserGroup;

class UserGroups extends Service
{
    // Properties
    // =========================================================================

    public static string $action = 'clone/user-group';
    public static string $id = 'groups';
    public static string $matchedRoute = 'settings/users';
    public static string $title = 'User Group';


    // Public Methods
    // =========================================================================

    public function setupClonedUserGroup($oldUserGroup, $name, $handle): UserGroup
    {
        $userGroup = new UserGroup();
        $userGroup->name = $name;
        $userGroup->handle = $handle;

        $this->cloneAttributes($oldUserGroup, $userGroup, [
            'description',
        ]);

        return $userGroup;
    }

    public function setupPermissions($oldUserGroup, $userGroup): void
    {
        $permissions = Craft::$app->getUserPermissions()->getPermissionsByGroupId($oldUserGroup->id);

        Craft::$app->getUserPermissions()->saveGroupPermissions($userGroup->id, $permissions);
    }

}