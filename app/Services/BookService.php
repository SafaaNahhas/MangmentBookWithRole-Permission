<?php
namespace App\Services;

use Exception;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Http\Resources\CatrgoryBook\BookResource;

/**
 * BookService handles business logic for book operations.
 */
class BookService
{

      /**
     * Retrieve all books.
     *
     * @return mixed
     * @throws Exception
     */


    public function getAllBooks()
    {
        try {
            // استرجاع جميع الكتب
            $books = Book::with('category')->get();

            if ($books->isEmpty()) {
                return response()->json(['error' => 'No Books Now'], 404);
            }

            // إرجاع البيانات باستخدام BookResource
            return BookResource::collection($books);
        } catch (Exception $e) {
            Log::error('Error retrieving all books: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }
    /**
     * Create a new book.
     *
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function createBook($data)
    {
        try {
            return Book::create($data);
        } catch (Exception $e) {
            Log::error('Error creating book: ' . $e->getMessage());
            throw $e;
        }
    }
      /**
     * Retrieve a book by its ID.
     *
     * @param int $id
     * @return mixed
     * @throws Exception
     */

    public function getBookById($id)
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return ['error' => 'No book found'];
            }

                return $book;}

        catch (Exception $e) {
                    Log::error('Error retrieving book with ID : ' . $e->getMessage());
                    throw $e;
                }
    }
     /**
     * Update an existing book.
     *
     * @param Book $book
     * @param array $data
     * @return mixed
     * @throws Exception
     */

     public function updateBook(Book $book, $data)
     {
         try {

        // $book = Book::find($id);
        // if (!$book) {
        //     return ['error' => 'No book found'];
        //     }
        $book->update($data);
        return $book;
        }  catch (Exception $e) {
            Log::error('Error retrieving book with ID : ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a book.
     *
     * @param Book $book
     * @return mixed
     * @throws Exception
     */
    public function deleteBook(Book $book)
    {
        try {

        if (!$book) {
            return ['error' => 'No book found'];
            }
            $book->delete();
        } catch (Exception $e) {
            Log::error('Error retrieving book with ID : ' . $e->getMessage());
            throw $e;
        }
    }
     /**
     * Restore a soft-deleted Book.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function restoreBook(int $id): void
    {
        try {
            $book = Book::withTrashed()->find($id);
            // if (!$book) {
            //     return ['error' => 'No book found'];
            //     }
            $book->restore();
        } catch (Exception $e) {
            Log::error('Failed to restore Book: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Permanently delete a Book .
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function forceDestroyBook(int $id): void
    {
        try {
            $Book = Book::withTrashed()->findOrFail($id);
            $Book->forceDelete();
        } catch (Exception $e) {
            Log::error('Failed to permanently delete Book: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get soft-deleted books.
     *
     * @return Collection
     */
    public function getSoftDeletedBooks(): Collection
    {
        return Book::onlyTrashed()->get();
    }

    /**
     * Get books by category ID.
     *
     * @param int $categoryId
     * @return Collection|null
     */
    public function getBooksByCategory(int $categoryId): ?Collection
    {
        $category = Category::find($categoryId);

        return $category ? $category->books : null;
    }

}
