<?php

namespace App\Http\Controllers\CategoryBook;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\BookService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest\StoreBookRequest;
use App\Http\Requests\BookRequest\UpdateBookRequest;

class BookController extends Controller
{
    /**
    * @var BookService
    */
    protected $bookService;

    /**
    * BookController constructor.
    *
    * @param BookService $bookService
    *
    */
    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;

    }

    /**
    * Display a listing of books.
     * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */    public function index()
    {
        $books= $this->bookService->getAllBooks();
        return response()->json([
            'data' => $books,
            ], 201);
    }


    /**
     * Store a newly created book in storage.
     *
     * @param StoreBookRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreBookRequest $request)
    {
        $book= $this->bookService->createBook($request->validated());
        return response()->json([
        'data' => $book,
        'message' => 'Book created successfully!'], 201);

    }

      /**
     * Display the specified book.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $book= $this->bookService->getBookById($id);
        return response()->json([
            'data' => $book,
            'message' => 'Show Book successfully!'], 201);
    }

   /**
     * Update the specified book in storage.
     *
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,UpdateBookRequest $request)
    {
        $book = $this->bookService->getBookById($id);
        $updatedBook = $this->bookService->updateBook($book, $request->validated());

        return response()->json([
            'data' => $updatedBook,
            'message' => 'Update Book successfully!'], 201);
    }
     /**
     * Remove the specified book from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = $this->bookService->getBookById($id);
        $destroybook = $this->bookService->deleteBook($book);
        return response()->json([
            'data' => $destroybook,
            'message' => 'Delete Book successfully!'], 201);
    }

    /**
     * Restore a soft-deleted Book.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore($id): JsonResponse
    {

            $this->bookService->restoreBook($id);
            return response()->json(['message' => 'Book  restored successfully'], 200);

    }

    /**
     * Permanently delete a Book.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function forceDestroy($id): JsonResponse
    {
            $this->bookService->forceDestroyBook($id);
            return response()->json(['message' => 'Book permanently deleted'], 200);

    }
    /**
     * Get the soft-deleted projects.
     *
     * @return JsonResponse
     */
    public function getSoftDeletedBook()
    {
        $books = $this->bookService->getSoftDeletedBooks();

        if ($books->isEmpty()) {
            return response()->json(['message' => 'No soft-deleted books found'], 404);
        }

        return response()->json($books, 200);
    }
    public function indexByCategory($categoryId)
{

    $books = $this->bookService->getBooksByCategory($categoryId);

        if (!$books) {
            return response()->json(['error' => 'No category found'], 404);
        }

        return response()->json($books, 200);
}

}
