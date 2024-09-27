<?php

namespace App\Http\Controllers\RolePermission;

use Exception;
use Illuminate\Http\Request;
use App\Services\RolesService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest\StoreRoleRequest;
use App\Http\Resources\RolePermission\RoleResource;
use App\Http\Requests\RoleRequest\UpdateRoleRequest;
use App\Http\Requests\RoleRequest\AssignPermissionsRequest;

class RolesController extends Controller
{
    /**
     * @var RolesService
     */
    protected $rolesService;

    /**
     * RolesController constructor.
     *
     * @param RolesService $rolesService
     */
    public function __construct(RolesService $rolesService)
    {
        $this->rolesService = $rolesService;
    }

    /**
     * Display a listing of all roles with their permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
            $roles = $this->rolesService->getAllRoles();
            return response()->json($roles, 200);

    }

    /**
     * Store a newly created role in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    //
    public function store(StoreRoleRequest $request) // Use Form Request here
    {

            $role = $this->rolesService->createRole($request->validated());
            return response()->json($role, 201);
    }
    /**
     * Update the specified role in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdateRoleRequest $request, $id) // Use Form Request here
    {

            $role = $this->rolesService->updateRole($id, $request->validated());
            return response()->json($role, 200);
    }
    /**
     * Remove the specified role from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
            $message = $this->rolesService->deleteRole($id);
            return response()->json(['message' => $message], 200);
    }

    /**
     * Assign permissions to a specific role.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $roleId
     * @return \Illuminate\Http\JsonResponse
     */


    public function assignPermissions(AssignPermissionsRequest $request, $roleId)
    {

            $message = $this->rolesService->assignPermissions($roleId, $request->input('permissions'));
            return response()->json(['message' => $message], 200);
    }
    /**
     * Remove a permission from a specific role.
     *
     * @param int $roleId
     * @param int $permissionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removePermission($roleId, $permissionId)
    {

            $message = $this->rolesService->removePermission($roleId, $permissionId);
            return response()->json(['message' => $message], 200);
    }
        /**
     * Display the specified role with its permissions.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
            $role = $this->rolesService->getRoleById($id);
            if (!$role) {
                return response()->json(['message' => 'Role not found'], 404);
            }
            return response()->json(new RoleResource($role), 200);
    }

}
