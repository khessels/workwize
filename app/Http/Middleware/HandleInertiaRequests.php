<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $roles = ['public'];
        if(Auth::check()){
            $roles = Auth::user()->roles->pluck('name');
        }

//        $user = $request->user();
//        $user->roles = $roles;
        return [
            ...parent::share($request),

            'auth' => [
                'user' => $request->user(),
                'roles' => $roles,
            ],
            'csrf_token' => csrf_token(),
        ];
    }
}
