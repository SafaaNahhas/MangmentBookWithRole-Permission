<?php

namespace App\Http\Controllers\RolePermission;

use Exception;
use App\Models\Roles;
use App\Models\Permissions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PermissionsService;
use App\Http\Requests\permissionRequest\StorePermissionRequest;
use App\Http\Requests\permissionRequest\UpdatePermissionRequest;

/**
 * Controller for managing permissions.
 */
class PermissionsController extends Controller
{
    protected $permissionsService;

    public function __construct(PermissionsService $permissionsService)
    {
        $this->permissionsService = $permissionsService;
    }

    /**
     * Display a listing of all permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $permissions = $this->permissionsService->getAllPermissions();
            return response()->json($permissions, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

        /**
     * Store a newly created permission.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePermissionRequest $request)
    {
            $permission = $this->permissionsService->createPermission($request->validated());
            $adminRole = Roles::where('name', 'مدير')->first();
            if ($adminRole) {
                $adminRole->permissions()->attach($permission->id);
            }
            return response()->json($permission, 201);
    }


    /**
     * Display the specified permission.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
            $permission = $this->permissionsService->getPermissionById($id);
            return response()->json($permission, 200);

    }

    /**
     * Update the specified permission.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(UpdatePermissionRequest $request, $id)
    {

            $permission = $this->permissionsService->updatePermission($request->validated(), $id);
            return response()->json($permission, 200);

    }
    /**
     * Remove the specified permission.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

            $message = $this->permissionsService->deletePermission($id);
            return response()->json(['message' => $message], 200);

    }

}

