<?php

namespace App\Services;

use Exception;
use App\Models\Roles;
use App\Models\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RolePermission\RoleResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RolesService
{
    /**
     * Retrieve all roles with their associated permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getAllRoles()
    {
        try {

            $roles = Roles::with('permissions' )->get();
            return RoleResource::collection($roles);

        } catch (Exception $e) {
            Log::error('Error fetching roles: ' . $e->getMessage());
            throw new Exception('Server Error', 500);
        }
    }

    /**
     * Create a new role.
     *
     * @param array $data
     * @return \App\Models\Roles
     * @throws \Exception
     */
        public function createRole(array $data)
    {
        try {
            // Create the role
            $role = Roles::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            return $role;
        } catch (Exception $e) {
            Log::error('Error creating role: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Update an existing role.
     *
     * @param int $id
     * @param array $data
     * @return \App\Models\Roles
     * @throws \Exception
     */

    public function updateRole(int $id, array $data)
    {
        try {
            $role = Roles::findOrFail($id);

            // Update the role fields
            if (isset($data['name'])) {
                $role->name = $data['name'];
            }

            if (isset($data['description'])) {
                $role->description = $data['description'];
            }

            $role->save();

            return $role;
        } catch (ModelNotFoundException $e) {
            Log::error('Role not found: ' . $e->getMessage());
            throw new Exception('Role not found', 404);
        } catch (Exception $e) {
            Log::error('Error updating role: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Delete a role and detach its permissions and users.
     *
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function deleteRole(int $id)
    {
        try {
            $role = Roles::findOrFail($id);

            // Detach permissions and users
            $role->permissions()->detach();
            $role->users()->detach();

            // Delete the role
            $role->delete();

            return 'Role deleted successfully';
        } catch (ModelNotFoundException $e) {
            Log::error('Role not found: ' . $e->getMessage());
            throw new Exception('Role not found', 404);
        } catch (Exception $e) {
            Log::error('Error deleting role: ' . $e->getMessage());
            throw new Exception('Server Error', 500);
        }
    }

    /**
     * Assign permissions to a role.
     *
     * @param int $roleId
     * @param array $permissions
     * @return string
     * @throws \Exception
     */
  
    public function assignPermissions(int $roleId, array $permissions)
    {
        try {
            $role = Roles::findOrFail($roleId);

            // Assign permissions without detaching existing ones
            $role->permissions()->syncWithoutDetaching($permissions);

            return 'Permissions assigned successfully';
        } catch (ModelNotFoundException $e) {
            Log::error('Role not found: ' . $e->getMessage());
            throw new Exception('Role not found', 404);
        } catch (Exception $e) {
            Log::error('Error assigning permissions: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Remove a permission from a role.
     *
     * @param int $roleId
     * @param int $permissionId
     * @return string
     * @throws \Exception
     */
    public function removePermission(int $roleId, int $permissionId)
    {
        try {
            $role = Roles::findOrFail($roleId);
            $permission = Permissions::findOrFail($permissionId);

            // Detach the permission from the role
            $role->permissions()->detach($permissionId);

            return 'Permission removed successfully';
        } catch (ModelNotFoundException $e) {
            Log::error('Role or Permission not found: ' . $e->getMessage());
            throw new Exception('Role or Permission not found', 404);
        } catch (Exception $e) {
            Log::error('Error removing permission: ' . $e->getMessage());
            throw $e;
        }
    }
    public function getRoleById($id)
{
    return Roles::with('permissions')->find($id);
}

}
