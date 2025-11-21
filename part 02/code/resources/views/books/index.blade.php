<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Library Book List</title>
    <!-- Basic Table Styling -->
    <style>
        body { font-family: sans-serif; margin: 20px; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; color: #333; }
        a { text-decoration: none; color: #007bff; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .action-button { padding: 5px 10px; border: none; border-radius: 3px; cursor: pointer; }
        .delete-btn { background: none; color: red; }
        .borrow-btn { background-color: #007bff; color: white; }
        .search-form { margin-bottom: 20px; display: flex; gap: 10px; }
        .search-form input[type="text"] { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 300px; }
        .search-form button { padding: 8px 15px; background-color: #555; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <!-- Logout -->
    <p style="text-align: right;">
        @auth
            Welcome, {{ auth()->user()->name }} ({{ auth()->user()->Role }}) | 
            @if (auth()->user()->Role === 'Customer')
                <a href="{{ route('my-loans') }}">My Loans</a> | 
            @endif
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #007bff; cursor: pointer; padding: 0;">Logout</button>
            </form>
        @else
            <!-- Guest Links -->
            <a href="{{ route('login') }}">Login</a> | <a href="{{ route('register') }}">Register</a>
        @endauth
    </p>

    <h1>Library Book List</h1>

    <!-- feedback meessaage -->
    @if (session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="error">
            {{ session('error') }}
        </div>
    @endif
    
    <!-- search books -->
    <form action="{{ route('books.index') }}" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by Title, ISBN, or Author" value="{{ $searchTerm ?? '' }}">
        <button type="submit">Search</button>
        <!-- clear -->
        <a href="{{ route('books.index') }}" style="align-self: center;">Clear Search</a>
    </form>


    <!-- Add book form(Librarian) -->
    @auth
        @if (auth()->user()->Role === 'Librarian')
            <p><a href="{{ route('books.create') }}" style="padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 15px;">+ Add New Book</a></p>
        @endif
    @endauth

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
                <tr>
                    <td>{{ $book->BookName }}</td>
                    <td>{{ $book->author->AuthorName ?? 'N/A' }}</td>
                    <td>{{ $book->ISBN }}</td>
                    <td>{{ $book->PublishYear }}</td>
                    <td>{{ $book->NoOfBooks }}</td>
                    <td>
                        <!-- hidden for guests -->
                        @auth
                            @if (auth()->user()->Role === 'Librarian')
                                <!-- Librarian edit and delete-->
                                <a href="{{ route('books.edit', $book->id) }}">Edit</a> | 
                                <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this book?')" class="action-button delete-btn">Delete</button>
                                </form>
                            @else
                                <!-- Customer borrow -->
                                @if ($book->NoOfBooks > 0)
                                    <form action="{{ route('borrow.store') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="action-button borrow-btn">Borrow</button>
                                    </form>
                                @else
                                    <span style="color: gray;">Out of Stock</span>
                                @endif
                            @endif
                        @endauth
                        @guest
                        <!--guest view-->
                             <span style="color: gray;">Login to Borrow</span>
                        @endguest
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No books found in the library.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>