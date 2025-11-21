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
        <?php if(auth()->guard()->check()): ?>
            Welcome, <?php echo e(auth()->user()->name); ?> (<?php echo e(auth()->user()->Role); ?>) | 
            <?php if(auth()->user()->Role === 'Customer'): ?>
                <a href="<?php echo e(route('my-loans')); ?>">My Loans</a> | 
            <?php endif; ?>
            <form action="<?php echo e(route('logout')); ?>" method="POST" style="display:inline;">
                <?php echo csrf_field(); ?>
                <button type="submit" style="background: none; border: none; color: #007bff; cursor: pointer; padding: 0;">Logout</button>
            </form>
        <?php else: ?>
            <!-- Guest Links -->
            <a href="<?php echo e(route('login')); ?>">Login</a> | <a href="<?php echo e(route('register')); ?>">Register</a>
        <?php endif; ?>
    </p>

    <h1>Library Book List</h1>

    <!-- feedback meessaage -->
    <?php if(session('success')): ?>
        <div class="success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="error">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>
    
    <!-- search books -->
    <form action="<?php echo e(route('books.index')); ?>" method="GET" class="search-form">
        <input type="text" name="search" placeholder="Search by Title, ISBN, or Author" value="<?php echo e($searchTerm ?? ''); ?>">
        <button type="submit">Search</button>
        <!-- clear -->
        <a href="<?php echo e(route('books.index')); ?>" style="align-self: center;">Clear Search</a>
    </form>


    <!-- Add book form(Librarian) -->
    <?php if(auth()->guard()->check()): ?>
        <?php if(auth()->user()->Role === 'Librarian'): ?>
            <p><a href="<?php echo e(route('books.create')); ?>" style="padding: 10px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; display: inline-block; margin-bottom: 15px;">+ Add New Book</a></p>
        <?php endif; ?>
    <?php endif; ?>

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
            <?php $__empty_1 = true; $__currentLoopData = $books; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($book->BookName); ?></td>
                    <td><?php echo e($book->author->AuthorName ?? 'N/A'); ?></td>
                    <td><?php echo e($book->ISBN); ?></td>
                    <td><?php echo e($book->PublishYear); ?></td>
                    <td><?php echo e($book->NoOfBooks); ?></td>
                    <td>
                        <!-- hidden for guests -->
                        <?php if(auth()->guard()->check()): ?>
                            <?php if(auth()->user()->Role === 'Librarian'): ?>
                                <!-- Librarian edit and delete-->
                                <a href="<?php echo e(route('books.edit', $book->id)); ?>">Edit</a> | 
                                <form action="<?php echo e(route('books.destroy', $book->id)); ?>" method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this book?')" class="action-button delete-btn">Delete</button>
                                </form>
                            <?php else: ?>
                                <!-- Customer borrow -->
                                <?php if($book->NoOfBooks > 0): ?>
                                    <form action="<?php echo e(route('borrow.store')); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="book_id" value="<?php echo e($book->id); ?>">
                                        <button type="submit" class="action-button borrow-btn">Borrow</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: gray;">Out of Stock</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if(auth()->guard()->guest()): ?>
                        <!--guest view-->
                             <span style="color: gray;">Login to Borrow</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6">No books found in the library.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html><?php /**PATH C:\xampp\htdocs\libraryMngmnt\resources\views/books/index.blade.php ENDPATH**/ ?>