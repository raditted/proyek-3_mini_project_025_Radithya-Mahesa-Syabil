<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * ini akan mengizinkan akses hanya jika role user cocok dengan salah satu role yang diizinkan
     *
     * contoh misal di route: middleware('role:koordinator,pemantau')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = $request->user()?->role;

        if (! $userRole) {
            abort(403);
        }

        $allowed = array_map(fn($r) => UserRole::from($r), $roles);

        if (! in_array($userRole, $allowed)) {
            abort(403);
        }

        return $next($request);
    }
}
