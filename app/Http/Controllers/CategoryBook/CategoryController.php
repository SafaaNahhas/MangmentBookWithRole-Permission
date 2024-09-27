<?php

namespace App\Http\Controllers\CategoryBook;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest\StoreCategoryRequest;
use App\Http\Requests\CategoryRequest\UpdateCategoryRequest;

class CategoryController extends Controller
{
    /**
    * @var CategoryService
    */
    protected $CategoryService;

    /**
    * CategoryController constructor.
    *
    * @param CategoryService $CategoryService
    *
    */
    public function __construct(CategoryService $CategoryService)
    {
        $this->CategoryService = $CategoryService;

    }

    /**
    * Display a listing of categorirs.
     * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */    public function index()
    {
        $categorirs= $this->CategoryService->getAllCategories();
        return response()->json([
            'data' => $categorirs,
            ], 201);
    }

   /**
     * Store a newly created Category in storage.
     *
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        $Category= $this->CategoryService->createCategory($request->validated());
        return response()->json([
        'data' => $Category,
        'message' => 'Category created successfully!'], 201);

    }

      /**
     * Display the specified Category.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $Category= $this->CategoryService->getCategoryById($id);
        return response()->json([
            'data' => $Category,
            'message' => 'Show Category successfully!'], 201);
    }

   /**
     * Update the specified Category in storage.
     *
     * @param UpdateCategoryRequest $request
     * @param Category $Category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,UpdateCategoryRequest $request)
    {
        $Category = $this->CategoryService->getCategoryById($id);
        $updatedCategory = $this->CategoryService->updateCategory($Category, $request->validated());

        return response()->json([
            'data' => $updatedCategory,
            'message' => 'Update Category successfully!'], 201);
    }
     /**
     * Remove the specified Category from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $Category = $this->CategoryService->getCategoryById($id);
        $destroyCategory= $this->CategoryService->deleteCategory($Category);
        return response()->json([
            'data' => $destroyCategory,
            'message' => 'Delete Category successfully!'], 201);
    }

    /**
     * Restore a soft-deleted Category and its books.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore($id): JsonResponse
    {

            $this->CategoryService->restoreCategory($id);
            return response()->json(['message' => 'Category and its books restored successfully'], 200);

    }

    /**
     * Permanently delete a Category and its books.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function forceDestroy($id): JsonResponse
    {
            $this->CategoryService->forceDestroyCategory($id);
            return response()->json(['message' => 'Category and its books permanently deleted'], 200);

    }
    /**
     * Get the soft-deleted projects.
     *
     * @return JsonResponse
     */
    public function getSoftDeletedCategory()
    {
        $Category = Category::onlyTrashed()->get();

        if ($Category->isEmpty()) {
            return response()->json(['message' => 'No soft-deleted Category found'], 404);
        }

        return response()->json($Category, 200);
    }
}
