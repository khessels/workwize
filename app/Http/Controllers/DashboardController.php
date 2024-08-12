<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Product;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Foundation\Application;
//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\isNull;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Dashboard');
    }
    public function index_backend(Request $request)
    {
        return Inertia::render('BackendDashboard');
    }
}
