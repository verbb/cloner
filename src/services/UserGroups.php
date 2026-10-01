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

    public function setupClonedUserGroup(UserGroup $oldUserGroup, string $name, string $handle): UserGroup
    {
        $userGroup = clone $oldUserGroup;
        $userGroup->id = null;
        $userGroup->uid = null;
        $userGroup->name = $name;
        $userGroup->handle = $handle;

        return $userGroup;
    }

    public function setupPermissions(UserGroup $oldUserGroup, UserGroup $userGroup): bool
    {
        $permissions = Craft::$app->getUserPermissions()->getPermissionsByGroupId($oldUserGroup->id);

        return Craft::$app->getUserPermissions()->saveGroupPermissions($userGroup->id, $permissions);
    }

}
