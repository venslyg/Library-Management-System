<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Borrowed Books</title>
    <!-- Basic Table Styling -->
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; color: #333; }
        a { text-decoration: none; color: #007bff; }
        .returned { background-color: #e6f7ff; color: #555; }
        .due { color: red; font-weight: bold; }
        .return-btn { background-color: #ff9800; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .top-nav { text-align: right; padding-bottom: 20px; border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="top-nav">
        <!-- Ensures user context is visible -->
        Welcome, {{ auth()->user()->name }} ({{ auth()->user()->Role }}) | 
        <a href="{{ route('books.index') }}">Book List</a> | 
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background: none; border: none; color: #007bff; cursor: pointer; padding: 0;">Logout</button>
        </form>
    </div>

    <h1>My Borrowed Books</h1>

    <!-- Display Feedback Messages (for successful returns, etc.) -->
    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Author</th>
                <th>Borrowed Date</th>
                <th>Due Date</th>
                <th>Return Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loans as $loan)
                <tr class="{{ $loan->ReturnDate ? 'returned' : '' }}">
                    <td>{{ $loan->book->BookName ?? 'N/A' }}</td>
                    <td>{{ $loan->book->author->AuthorName ?? 'N/A' }}</td>
                    <td>{{ $loan->BorrowDate }}</td>
                    <!-- Highlighting if the book is overdue and not yet returned -->
                    <td class="{{ !$loan->ReturnDate && now()->gt($loan->DueDate) ? 'due' : '' }}">
                        {{ $loan->DueDate }}
                    </td>
                    <td>{{ $loan->ReturnDate ?? 'Active Loan' }}</td>
                    <td>
                        @if (!$loan->ReturnDate)
                            <!-- Return form submits to BorrowController@update -->
                            <form action="{{ route('borrow.update', $loan->id) }}" method="POST">
                                @csrf
                                <!-- We use POST but Laravel interprets it as PUT/PATCH -->
                                @method('POST') 
                                <button type="submit" class="return-btn">Return Book</button>
                            </form>
                        @else
                            Returned
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">You have no active or past loans.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>