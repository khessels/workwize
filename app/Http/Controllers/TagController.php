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
    public function tree(Request $request)
    {
        try{
            $topics = Topic::where('visible', 'YES')->with('tags', function($q){
                $q->where('visible', 'YES');
            })->whereHas('tags', function($q){
                $q->where('visible', 'YES');
            })->get()->toArray();
            if($request->hasHeader('x-response-format')){
                if($request->header('x-response-format') == 'primereact'){
                    foreach( $topics as &$topic) {
                        $topic[ 'key' ] = $topic[ 'id' ] ;
                        $topic[ 'children' ] = $topic[ 'tags' ];
                        unset($topic[ 'tags' ]);
                        $topic[ 'label'] = $topic[ 'name'];
                        $topic[ 'data'] = $topic[ 'name'];
                        unset($topic[ 'name' ]);
                        foreach( $topic[ 'children'] as &$child) {
                            $child['label'] = $child[ 'name'];
                            $child['data'] = $child[ 'name'];
                            unset($child[ 'name' ]);
                            $child['key'] = $child['id'];
                        }
                    }
                }
            }
            return $this->_response($request, $topics);
        }catch(\Exception $e){
            $m = $e->getMessage();
            error_log($e->getMessage());
        }
        return $this->_response($request, 'ERROR');
    }
    public function products(Request $request)
    {
        $tags = $request->tags;
        $products = Product::where('active', 'YES')->with( [ 'ProductTags', function( $q) use ( $tags){
            $q->whereIn('tag_id', $tags);
        }])->get();
        return $this->_response($request, $products);
    }

    public function getTopics(Request $request): \Illuminate\Http\JsonResponse
    {
        if($request->header('format')){
            $topics = Topic::select(DB::raw('name, id as code'))->get();
        }else{
            $topics = Tag::select(DB::raw('id, name'))->get();
        }
        return $this->_response($request, $topics);
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
        return $this->_response($request, $tags);
    }
}
