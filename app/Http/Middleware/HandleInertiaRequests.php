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
        $isPublic = true;
        $isAdmin = false;
        $isCustomer = false;
        $isSupplier = false;
        if(Auth::check()){
            $roles = Auth::user()->roles->pluck('name');
            foreach($roles as $role){
                $isPublic = false;
                if($role === 'admin'){
                    $isAdmin = true;
                }
                if($role === 'customer'){
                    $isCustomer = true;
                }
                if($role === 'supplier'){
                    $isSupplier = true;
                }
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'isPublic' => $isPublic,
                'isAdmin' => $isAdmin,
                'isCustomer' => $isCustomer,
                'isSupplier' => $isSupplier
            ],
            'csrf_token' => csrf_token(),
        ];
    }
}
