<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Book</title>
</head>
<body>

    <a href="{{ route('books.index') }}">Back to Book List</a>

    <h1>Add New Book</h1>

    <!-- validations -->
    @if ($errors->any())
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('books.store') }}" method="POST">
        @csrf
        
        <div>
            <label for="BookName">Title:</label>
            <input type="text" id="BookName" name="BookName" value="{{ old('BookName') }}" required>
        </div>
        
        <div>
            <label for="author_id">Author:</label>
            <select id="author_id" name="author_id" required>
                <option value="">Select Author</option>
                @foreach ($authors as $author)
                    <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                        {{ $author->AuthorName }}
                    </option>
                @endforeach
            </select>
        </div>

        <p style="color: gray;">If the author is missing, try manually adding them first.</p>
        
        <div>
            <label for="ISBN">ISBN:</label>
            <input type="text" id="ISBN" name="ISBN" value="{{ old('ISBN') }}" required>
        </div>
        
        <div>
            <label for="PublishYear">Publish Year:</label>
            <input type="number" id="PublishYear" name="PublishYear" value="{{ old('PublishYear') }}" required>
        </div>
        
        <div>
            <label for="NoOfBooks">Stock Quantity:</label>
            <input type="number" id="NoOfBooks" name="NoOfBooks" value="{{ old('NoOfBooks', 1) }}" required>
        </div>
        
        <button type="submit" style="margin-top: 15px;">Add Book</button>
    </form>

</body>
</html>