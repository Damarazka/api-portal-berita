<?php
namespace App\Http\Controllers;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
class PostController extends Controller
{
    public function index(){
        $posts = Post::all();
        // return response()->json(['data' => $posts]);
        //return PostResource::collection($posts);
        return PostDetailResource::collection($posts->loadMissing('writer:id,username', 'comments:id,post_id,user_id,comment_content'));
    }

    public function show($id){
        $post = Post::with('writer:id,username', 'comments:id,post_id,user_id,comment_content')->findOrFail($id);
        return new PostDetailResource($post);
    }
    public function show2($id){
        $post = Post::findOrFail($id);
        return new PostDetailResource($post);
    }

    public function store(Request $request){
        $request ->validate([
            'title' => 'required|max:225',
            'news_content'=>'required',
        ]);
        //return response()->json('sudah dapat digunakan');
        $request['author']= Auth::user()->id;
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id){
        $request ->validate([
            'title' => 'required|max:225',
            'news_content'=>'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function delete($id){
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => "data successfully deleted"
        ]);
    }
}