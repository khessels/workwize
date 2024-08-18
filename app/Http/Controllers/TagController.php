<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Tag;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

use function PHPUnit\Framework\isNull;

class TagController extends Controller
{
    public function getTopics(Request $request): \Illuminate\Http\JsonResponse
    {
        if($request->header('format')){
            $topics = Topic::select(DB::raw('name, id as code'))->get();
        }else{
            $topics = Tag::select(DB::raw('id, name'))->get();
        }
        return response()->json( $topics);
    }
    public function getTags(Request $request, $topic = null): \Illuminate\Http\JsonResponse
    {
        if($request->header('format')){
            $query = Tag::query();
            $query = $query->select(DB::raw('name, id as code'));
            if($topic){
                $query = $query->where('topic_id', $topic);
            }
            $tags = $query->get();
        }else{
            $query = Tag::query();
            $query = $query->select(DB::raw('id, name'));
            if($topic){
                $query = $query->where('topic_id', $topic);
            }
            $tags = $query->get();

        }
        return response()->json( $tags);
    }

}
