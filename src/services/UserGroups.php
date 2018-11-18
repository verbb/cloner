<?php
namespace verbb\cloner\services;

use verbb\cloner\Cloner;
use verbb\cloner\base\Service;

use Craft;
use craft\elements\User;
use craft\models\UserGroup;

class UserGroups extends Service
{
    // Properties
    // =========================================================================

    public static $matchedRoute = 'settings/users';
    public static $id = 'groups';
    public static $title = 'User Group';
    public static $action = 'clone/user-group';


    // Public Methods
    // =========================================================================

    public function setupClonedUserGroup($oldUserGroup, $name, $handle)
    {
        $userGroup = new UserGroup();
        $userGroup->name = $name;
        $userGroup->handle = $handle;

        return $userGroup;
    }

    public function setupPermissions($oldUserGroup, $userGroup)
    {   
        $permissions = Craft::$app->getUserPermissions()->getPermissionsByGroupId($oldUserGroup->id);

        Craft::$app->getUserPermissions()->saveGroupPermissions($userGroup->id, $permissions);
    }

}