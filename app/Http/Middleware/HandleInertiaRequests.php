<?php

namespace App\Http\Middleware;

use App\Models\Role;
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
        $auth = [
            'user' => $request->user(),
            'isPublic' => $isPublic,
        ];

        // get roles and initialize all roles false and public role true
        $roles = Role::all()->pluck('name');
        foreach($roles as $role){
            $roleIs = "is" . ucfirst($role);
            $auth[$roleIs] = false;
        }

        // if user has been authenticated iterate all roles assigned to user and set user assigned roles to true
        if(Auth::check()){
            $roles = Auth::user()->roles->pluck('name');
            $auth['isPublic'] = false;
            foreach($roles as $role){
                $roleIs = "is" . ucfirst($role);
                $auth[$roleIs] = true;
            }
        }

        return [
            ...parent::share($request),
            'auth' => $auth,
            'roles' => $roles,
            'csrf_token' => csrf_token(),
        ];
    }
}
