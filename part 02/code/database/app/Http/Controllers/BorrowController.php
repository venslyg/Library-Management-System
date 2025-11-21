<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BorrowController extends Controller
{
    //display method
    public function myLoans()
    {
        $loans = Borrow::where('user_id', Auth::id())
            ->with(['book', 'book.author'])
            ->orderByRaw('ISNULL(ReturnDate) desc, DueDate asc')
            ->get();

        return view('borrow.my_loans', compact('loans'));
    }

    //borrow logic
    public function store(Request $request)
    {
        // Validation
        $request->validate(['book_id' => 'required|exists:books,id']);

        $book = Book::find($request->book_id);
        $user = Auth::user();

        $isBorrowed = Borrow::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereNull('ReturnDate')
            ->exists();

        if ($isBorrowed) {
            return back()->with('error', 'You have already borrowed this copy and not yet returned it.');
        }
        if ($book->NoOfBooks <= 0) {
            return back()->with('error', 'This book is currently out of stock and cannot be borrowed.');
        }

        $borrowDate = Carbon::now()->toDateString();
        $dueDate = Carbon::now()->addDays(7)->toDateString();

        // add borrow record
        Borrow::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'BorrowDate' => $borrowDate,
            'DueDate' => $dueDate,
        ]);

        // update book stock
        $book->decrement('NoOfBooks');

        return back()->with('success', "Book successfully borrowed! Please return by {$dueDate}.");
    }

    public function update(Borrow $borrow)
    {
        // FIX: Use Auth::id() instead of auth()->id()
        // Only the user who borrowed the book can mark it as returned
        if ($borrow->user_id !== Auth::id()) {
            // Log this security attempt
            Log::warning("Unauthorized return attempt by User ID: " . Auth::id() . " for Borrow ID: " . $borrow->id);
            return back()->with('error', 'Unauthorized: You can only return books you personally borrowed.');
        }

        // The books which is marked returned cannot be returned again
        if (!is_null($borrow->ReturnDate)) {
            return back()->with('error', 'This book has already been marked as returned.');
        }

        // update borrow record
        $borrow->update(['ReturnDate' => Carbon::now()->toDateString()]);

        // update book stock
        $borrow->book->increment('NoOfBooks');

        return redirect()->route('my-loans')->with('success', 'Book successfully returned!');
    }
}
