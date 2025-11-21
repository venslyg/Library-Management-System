<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], select { width: 300px; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        button[type="submit"] { padding: 10px 15px; background-color: #ff9800; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error-list { color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px; list-style-type: none; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #555; }
    </style>
</head>
<body>

    <a href="{{ route('books.index') }}" class="back-link">← Back to Book List</a>

    <h1>Edit Book: {{ $book->BookName }}</h1>

    <!-- Validations-->
    @if ($errors->any())
        <ul class="error-list">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    
    <form action="{{ route('books.update', $book->id) }}" method="POST">
        @csrf
        @method('PUT') 
        
        <div>
            <label for="BookName">Title:</label>
            <input type="text" id="BookName" name="BookName" value="{{ old('BookName', $book->BookName) }}" required>
        </div>
        
        <div>
            <label for="author_id">Author:</label>
            <select id="author_id" name="author_id" required>
                <option value="">Select Author</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author_id', $book->author_id) == $author->id ? 'selected' : '' }}>
                        {{ $author->AuthorName }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div>
            <label for="ISBN">ISBN (Unique):</label>
            <input type="text" id="ISBN" name="ISBN" value="{{ old('ISBN', $book->ISBN) }}" required>
        </div>
        
        <div>
            <label for="PublishYear">Publish Year:</label>
            <input type="number" id="PublishYear" name="PublishYear" value="{{ old('PublishYear', $book->PublishYear) }}" required min="1900" max="{{ date('Y') }}">
        </div>
        
        <div>
            <label for="NoOfBooks">Stock Quantity:</label>
            <input type="number" id="NoOfBooks" name="NoOfBooks" value="{{ old('NoOfBooks', $book->NoOfBooks) }}" required min="0">
        </div>
        
        <button type="submit">Update Book</button>
    </form>

</body>
</html>