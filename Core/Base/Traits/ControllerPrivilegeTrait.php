<?php
/*
 * This file is part of the Abc package.
 *
 * This source code is for educational purposes only.
 * It is not recommended to use it in production as it is.
 */

declare(strict_types = 1);

namespace Abc\Base\Traits;

//use _Modules\Admin\Permission\PermissionModel;
//use _Modules\Admin\RolePermission\RolePermissionModel;
//use _Modules\Admin\UserRole\UserRoleModel;
use Abc\Auth\Admin\Models\CorePermissionModel;
use Abc\Auth\Admin\Models\CoreRolePermissionModel;
use Abc\Auth\Admin\Models\CoreUserRoleModel;
use Abc\Auth\Authorized;
use Abc\Base\BaseModel;
use Abc\Utility\Log;
use Exception;

trait ControllerPrivilegeTrait
{
    public function getUserRole(): self
    {
        Log::evo_log('Getting user role');
        if (class_exists($user_role_model = CoreUserRoleModel::class)) {
//            //Log::evo_log('UserRoleModel exists');
//            echo 'UserRoleModel exists....';
            $user_role_model_object = new $user_role_model();
            if ($user_role_model_object instanceof BaseModel) {
//                //Log::evo_log('CoreUserRoleModel object is an instance of BaseModel');
                $user_role = $user_role_model_object->getUserRoleID(Authorized::getCurrentSessionID());
//                //Log::evo_log('Setting user\'s role');
                $this->user_role = $user_role;
//                print_r($this->user_role);
//                exit;
                return $this;
            }
        }
    }

    public function getRolePermissions(): self
    {
        Log::evo_log('Getting a role\'s permissions');
        if (class_exists($role_permission_model = CoreRolePermissionModel::class)) {
//            //Log::evo_log('RolePermissionModel exists');
//            echo "RolePermissionModel exists: $role_permission_model....";
            $role_permission_model_object = new $role_permission_model();
//            echo 'RolePermissionModel object created...';
            if ($role_permission_model_object instanceof BaseModel) {
//                //Log::evo_log('Object is an instance of BaseModel');
//                echo 'Object is an instance of BaseModel....';
                $permissions = $role_permission_model_object->getRepository()->findAll();
//                //Log::evo_log('Setting permissions in an array');
                $this->perms = $permissions;
//                print_r($this->perms);
//                exit;
                return $this;
            }
        }
    }

    public function matchPermission($permission): bool
    {
        Log::evo_log('Searching for ' . $permission . ' permission in the permissions array');
//        echo $permission . '<br>';
        if (is_array($this->perms) && count($this->perms) > 0) {
            foreach ($this->perms as $perm) {
//                print_r($perm['permission_id']);echo '<br>';
//                print_r($this->user_role->role_id);echo '<br>';
//                exit;
                try {
                    if ($perm['role_id'] === $this->user_role->role_id) {
                        $db_permission = $this->getPermission($perm['permission_id']);
//                        echo $db_permission . '<br>';

                        if ($db_permission === $permission) {
//                            echo 'Match<br>';
                            Log::evo_log('Permission matched: ' . $permission);
                            return true;
                        }/* else {
                            'NOT match<br>';
                        }*/
                    }
                } catch (Exception $e) {
                }
            }
            Log::evo_log('Permission NOT matched: ' . $permission);
            exit;
        }
        Log::evo_log('No permissions found in the array');
        return false;
    }

    private function getPermission($id)
    {
//        Log::evo_log('Getting permission');
        return (new CorePermissionModel())->getNameForSelectField($id);
    }
}
