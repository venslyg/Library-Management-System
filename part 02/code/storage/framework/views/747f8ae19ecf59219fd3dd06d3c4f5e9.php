
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LMS Login</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 350px; }
        h1 { text-align: center; color: #333; margin-bottom: 20px; }
        form div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="email"], input[type="password"] { width: calc(100% - 16px); padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; }
        button:hover { background-color: #0056b3; }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .register-link { text-align: center; margin-top: 20px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Library Management System</h1>
        
        <?php if(session('success')): ?>
            <div style="color: green; margin-bottom: 15px;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="/login">
            <?php echo csrf_field(); ?>

            <div>
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="error"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit">Log In</button>
        </form>
        
        <div class="register-link">
            Don't have an account? <a href="<?php echo e(route('register')); ?>">Register Here</a>
        </div>
    </div>
</body>
</html><?php /**PATH C:\xampp\htdocs\libraryMngmnt\resources\views/auth/login.blade.php ENDPATH**/ ?>