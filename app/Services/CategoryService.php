<?php
namespace App\Services;

use Exception;
use App\Services;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\CatrgoryBook\CategoryResource;

class CategoryService
{
     /**
     * Retrieve all Categories.
     *
     * @return mixed
     * @throws Exception
     */


    public function getAllCategories()
    {
        try {
            $categories = Category::with('books')->get();

            if ($categories->isEmpty()) {
                return response()->json(['error' => 'No Category Now'], 404);
            }
            return CategoryResource::collection($categories);
        } catch (Exception $e) {
            Log::error('Error retrieving all Categories: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
      /**
     * Create a new Category.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function createCategory($data)
    {
        try {
            return Category::create($data);
        } catch (Exception $e) {
            Log::error('Error creating Category: ' . $e->getMessage());
            throw $e;
        }
    }
      /**
     * Retrieve a Category by its ID.
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */

    public function getCategoryById($id)
    {
        try {
            $Category = Category::find($id);

            if (!$Category) {
                return ['error' => 'No Category found'];
            }

                return $Category;}

        catch (Exception $e) {
                    Log::error('Error retrieving Category with ID : ' . $e->getMessage());
                    throw $e;
                }
    }
     /**
     * Update an existing Category.
     *
     * @param Category $Category
     * @param array $data
     * @return mixed
     * @throws Exception
     */

     public function updateCategory(Category $Category, $data)
     {
         try {

        // $book = Book::find($id);
        // if (!$book) {
        //     return ['error' => 'No book found'];
        //     }
        $Category->update($data);
        return $Category;
        }  catch (Exception $e) {
            Log::error('Error retrieving Category with ID : ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a Category.
     *
     * @param Category $Category
     * @return mixed
     * @throws Exception
     */
    public function deleteCategory(Category $Category)
    {
        try {
            $Category->books()->delete();
            $Category->delete();
        } catch (Exception $e) {
            Log::error('Error retrieving Category with ID : ' . $e->getMessage());
            throw $e;
        }
    }
      /**
     * Restore a soft-deleted Category and its books.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function restoreCategory(int $id): void
    {
        try {
            $Category = Category::withTrashed()->findOrFail($id);
            $Category->restore();
            $Category->books()->restore();
        } catch (Exception $e) {
            Log::error('Failed to restore Category: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Permanently delete a Category and its books.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function forceDestroyCategory(int $id): void
    {
        try {
            $Category = Category::withTrashed()->findOrFail($id);
            $Category->books()->forceDelete();
            $Category->forceDelete();
        } catch (Exception $e) {
            Log::error('Failed to permanently delete Category: ' . $e->getMessage());
            throw $e;
        }
    }
}

