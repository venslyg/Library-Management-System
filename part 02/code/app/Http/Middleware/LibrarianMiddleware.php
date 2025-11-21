<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LibrarianMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check if authenticated AND role is Librarian
        if (Auth::check() && Auth::user()->Role === 'Librarian') {
            return $next($request);
        }
        return redirect('/books')->with('error', 'Unauthorized access. Only Librarians can manage books.');
    }
}
