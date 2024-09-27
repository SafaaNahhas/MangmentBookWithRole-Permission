<?php

namespace App\Services;

use App\Models\Permissions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

/**
 * Service class for managing permissions.
 */
class PermissionsService
{
    /**
     * Retrieve all permissions.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getAllPermissions()
    {
        try {
            $permissions = Permissions::all();
            return $permissions;
        } catch (Exception $e) {
            Log::error('Error fetching permissions: ' . $e->getMessage());
            throw new Exception('Failed to retrieve permissions.', 500);
        }
    }

    /**
     * Create a new permission.
     *
     * @param array $data
     * @return \App\Models\Permissions
     * @throws \Exception
     */
    
    public function createPermission(array $data)
    {
        try {
            $permission = Permissions::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            return $permission;
        } catch (Exception $e) {
            Log::error('Error creating permission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Retrieve a specific permission by ID.
     *
     * @param int $id
     * @return \App\Models\Permissions
     * @throws \Exception
     */
    public function getPermissionById(int $id)
    {
        try {
            $permission = Permissions::find($id);
            if (!$permission) {
                throw new Exception('Permission not found.', 404);
            }

            return $permission;
        } catch (Exception $e) {
            Log::error('Error fetching permission: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update an existing permission.
     *
     * @param array $data
     * @param int $id
     * @return \App\Models\Permissions
     * @throws \Exception
     */

    public function updatePermission(array $data, int $id)
    {
        try {
            $permission = Permissions::find($id);
            if (!$permission) {
                throw new Exception('Permission not found.', 404);
            }

            if (isset($data['name'])) {
                $permission->name = $data['name'];
            }

            if (isset($data['description'])) {
                $permission->description = $data['description'];
            }

            $permission->save();

            return $permission;
        } catch (Exception $e) {
            Log::error('Error updating permission: ' . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Delete a specific permission.
     *
     * @param int $id
     * @return string
     * @throws \Exception
     */
    public function deletePermission(int $id)
    {
        try {
            $permission = Permissions::find($id);
            if (!$permission) {
                throw new Exception('Permission not found.', 404);
            }

            // Detach from roles if any
            $permission->roles()->detach();

            $permission->delete();

            return 'Permission deleted successfully.';
        } catch (Exception $e) {
            Log::error('Error deleting permission: ' . $e->getMessage());
            throw $e;
        }
    }
}
