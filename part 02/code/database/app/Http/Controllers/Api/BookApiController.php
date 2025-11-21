<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Auth;

class BookApiController extends Controller
{
    //Display the resource (GET /api/books).

    public function index()
    {
        // Fetch all books with the author relationship
        return BookResource::collection(Book::with('author')->get());
    }

    public function store(Request $request)
    {
        // Role Check
        if (Auth::user()->Role !== 'Librarian') {
            return response()->json(['error' => 'Permission Denied. Must be a Librarian.'], 403);
        }

        // Validation
        $validated = $request->validate([
            'BookName' => 'required|string|max:255',
            'ISBN' => 'required|string|unique:books,ISBN',
            'PublishYear' => 'required|integer|min:1900|max:' . date('Y'),
            'NoOfBooks' => 'required|integer|min:1',
            'author_id' => 'required|exists:authors,id',
        ]);

        // Creation
        $book = Book::create($validated);

        // Response
        return new BookResource($book);
    }

    //Display the specific resource (GET /api/books/{id}).
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    //update the specified resource in storage (PUT /api/books/{id}).
    public function update(Request $request, Book $book)
    {
        if (Auth::user()->Role !== 'Librarian') {
            return response()->json(['error' => 'Permission Denied. Must be a Librarian.'], 403);
        }

        // Validation
        $validated = $request->validate([
            'BookName' => 'required|string|max:255',
            'ISBN' => 'required|string|unique:books,ISBN,' . $book->id,
            'PublishYear' => 'required|integer|min:1900|max:' . date('Y'),
            'NoOfBooks' => 'required|integer|min:0',
            'author_id' => 'required|exists:authors,id',
        ]);

        $book->update($validated);

        return new BookResource($book);
    }

    //Remove the specified resource from storage (DELETE /api/books/{id}).
    public function destroy(Book $book)
    {
        if (Auth::user()->Role !== 'Librarian') {
            return response()->json(['error' => 'Permission Denied. Must be a Librarian.'], 403);
        }

        $book->delete();
        return response()->json(null, 204);
    }
}
