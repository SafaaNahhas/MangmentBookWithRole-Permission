<?php
namespace App\Http\Controllers\RolePermission;

use Exception;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\StoreUserRequest;
use App\Http\Resources\RolePermission\UserResource;
use App\Http\Requests\UserRequest\AssignRoleRequest;
use App\Http\Requests\UserRequest\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * Create an instance of UserController.
     *
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a list of users with their roles and permissions.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
            $users = $this->userService->getAllUsers();
            return response()->json($users, 200);

    }

    /**
     * Add a new user with a specific role and permissions.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function store(StoreUserRequest $request)
    {

            $user = $this->userService->createUser($request->all());
            return response()->json($user, 201);
    }
    /**
     * Update a user's details, role, and permissions.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, $id)
    {
            $user = $this->userService->updateUser($request->validated(), $id);
            return response()->json($user, 200);

    }
    /**
     * Delete a user.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {

            $response = $this->userService->deleteUser($id);
            return response()->json($response, 200);
    }

    /**
     * Assign a role to a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */

    public function assignRole(AssignRoleRequest $request, $userId)
    {
            $roleId = $request->input('roles_id');
            $user = $this->userService->assignRoleToUser($userId, $roleId);
            return response()->json($user, 200);
    }
    /**
     * Show a specific user with their role and permissions.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {

            $user = $this->userService->getUserById($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            return response()->json(new UserResource($user), 200);

    }
}

