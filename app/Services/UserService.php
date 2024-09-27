<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Roles;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\RolePermission\UserResource;

class UserService
{
     /**
     * Get a user by their ID, including their roles and permissions.
     *
     * @param int $id The ID of the user to retrieve.
     * @return \App\Models\User|null The user object or null if not found.
     * @throws \Exception If the user is not found or any error occurs.
     */
    public function getUserById($id)
    {
        try {
            // البحث عن المستخدم مع دوره وصلاحياته
            $user = User::with(['roles', 'roles.permissions'])->find($id);

            if (!$user) {
                throw new Exception('User not found', 404);
            }

            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
     /**
     * Get a list of all users, including their roles and permissions.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection The list of users as a resource collection.
     * @throws \Exception If an error occurs while fetching the users.
     */
    public function getAllUsers()
    {
        try {
            $users = User::with('roles.permissions')->get();
            return UserResource::collection($users);
        } catch (Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            throw new Exception('Server Error', 500);
        }
    }

    /**
     * Create a new user with the specified roles and permissions.
     *
     * @param array $data The data for creating the user, including name, email, password, and roles.
     * @return UserResource The newly created user resource.
     * @throws \Exception If an error occurs during user creation.
     */
    public function createUser(array $data)
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (isset($data['roles'])) {
                $user->roles()->attach($data['roles']);
            }

            $user->load(['roles.permissions']);

            return new UserResource($user);
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            throw $e;
        }
    }

     /**
     * Update an existing user by their ID, including updating roles if provided.
     *
     * @param array $data The updated data including name, email, password, and roles.
     * @param int $id The ID of the user to update.
     * @return UserResource The updated user resource.
     * @throws \Exception If the user is not found or any error occurs during the update.
     */
    public function updateUser(array $data, $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                throw new Exception('User not found', 404);
            }

            // تحديث الحقول إذا كانت موجودة
            if (isset($data['name'])) {
                $user->name = $data['name'];
            }

            if (isset($data['email'])) {
                $user->email = $data['email'];
            }

            if (isset($data['password'])) {
                $user->password = Hash::make($data['password']);
            }

            $user->save();

            if (isset($data['roles'])) {
                $user->roles()->sync($data['roles']);
            }

            $user->load(['roles.permissions']);

            return new UserResource($user);
        } catch (Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a user by their ID, detaching roles before deletion.
     *
     * @param int $id The ID of the user to delete.
     * @return array A success message indicating the user was deleted.
     * @throws \Exception If the user is not found or any error occurs during deletion.
     */
    public function deleteUser($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                throw new Exception('User not found', 404);
            }

            $user->roles()->detach();
            $user->delete();

            return ['message' => 'User deleted successfully'];
        } catch (Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
         * Assign a role to a specific user by their IDs.
         *
         * @param int $userId The ID of the user to assign the role to.
         * @param int $roleId The ID of the role to assign to the user.
         * @return UserResource The updated user resource with the assigned role.
         * @throws \Exception If the user or role is not found or any error occurs.
         */
    public function assignRoleToUser($userId, $roleId)
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                throw new Exception('User not found', 404);
            }

            $role = Roles::find($roleId);
            if (!$role) {
                throw new Exception('Role not found', 404);
            }

            if ($user->roles()->where('roles_id', $roleId)->exists()) {
                return 'User already has this role';
            }

            $user->roles()->attach($roleId);

            $user->load(['roles.permissions']);

            return new UserResource($user);
        } catch (Exception $e) {
            Log::error('Error assigning role to user: ' . $e->getMessage());
            throw $e;
        }
    }


}
