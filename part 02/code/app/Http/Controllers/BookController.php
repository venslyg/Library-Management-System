<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class BookController extends Controller
{
    // --------------------------------------------------------------------------------
    // 1. VIEW ALL BOOKS & SEARCH (INDEX) - Accessible by Everyone
    // --------------------------------------------------------------------------------
    public function index(Request $request)
    {
        // FIX: Initialize $searchTerm to null/empty string
        $searchTerm = null;

        // Start building the query with the Author relationship (Eager Loading)
        $query = Book::with('author');

        // Searching Books Use Case
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search; // Only define it here if a search occurred
            $query->where(function ($q) use ($searchTerm) {
                $q->where('BookName', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('ISBN', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('author', function ($q) use ($searchTerm) {
                        $q->where('AuthorName', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        $books = $query->get();

        // Pass both variables to the view (searchTerm is now guaranteed to exist)
        return view('books.index', compact('books', 'searchTerm'));
    }
    // Add books (Lubrarian)
    public function create()
    {
        $authors = Author::orderBy('AuthorName')->get();
        return view('books.create', compact('authors'));
    }

    // 3. Store books(Librarian)
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'BookName' => 'required|string|max:255',
            'ISBN' => 'required|string|unique:books,ISBN',
            'PublishYear' => 'required|integer|min:1900|max:' . date('Y'),
            'NoOfBooks' => 'required|integer|min:1',
            'author_id' => 'required|exists:authors,id',
        ]);

        Book::create($validatedData);

        return redirect()->route('books.index')->with('success', 'Book added successfully!');
    }


    // 4. Edit book details logic (librarian)

    public function edit(Book $book)
    {
        $authors = Author::orderBy('AuthorName')->get();
        return view('books.edit', compact('book', 'authors'));
    }
    // 5. Update book details logic (librarian)
    public function update(Request $request, Book $book)
    {
        $validatedData = $request->validate([
            'BookName' => 'required|string|max:255',
            'ISBN' => 'required|string|unique:books,ISBN,' . $book->id,
            'PublishYear' => 'required|integer|min:1900|max:' . date('Y'),
            'NoOfBooks' => 'required|integer|min:0',
            'author_id' => 'required|exists:authors,id',
        ]);

        $book->update($validatedData);

        return redirect()->route('books.index')->with('success', 'Book updated successfully!');
    }


    // 6. Delete book logic (librarian)

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }
}
