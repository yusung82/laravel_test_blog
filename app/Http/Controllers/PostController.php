<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Http\Requests\PostRequest;
class PostController extends Controller
{
    /*
     * model
     * 1:1
     * 1:N  hasMany
     * N:N
     */

    //list
    public function index(){
        $posts = Post::orderby('created_at', 'desc')
            ->with('comments','categories','comments.user', 'user')
            ->paginate(10);
//        //collection
//        $filtered = $posts->filter( function ($value) {
//            return $value->id % 2 === 1;
//        });
//        return $filtered;

        return response()->json( $posts);
    }

    //add
    public function create(PostRequest $request){

//        $subject = $request->input('subject');
//        $content = $request->input('content');

//        $post = new Post();
//        $post->subject = $subject;
//        $post->content = $content;
//        $post->save();

        $params = $request->only(['subject', 'content']);
        $params['user_id'] = $request->user()->id;
        $post = Post::create($params);

        //카테고리 등록 N:N
        $ids = $request->input('category_ids');
        $post->categories()->sync($ids);

        $result = Post::where('id', $post->id)
            ->with(['user', 'categories'])
            ->first();

        return response()->json($result);
    }

    //read
    public function read($id){
        $post = Post::where('id', $id)
            ->with('comments','comments.user','user')
            ->first();
        //$post = Post::find($id); //축약식 - 단일데이터 빠르게

        if(!$post){
            return  response()
                ->json(['message','주회할 데이터가 없습니다.'], 404);
        }

        return response()->json($post);
    }

    //update
    public function update(Request $request, $id){
        $post = Post::find( $id );
        if(!$post) return response()->json(['message' => '조회할 데이터가 없습니다.'], 404);

        $user = $request->user();
        if($user->id !== $post->user_id) return response()->json(['message' => '권한이 없습니다.'], 403);

        $post = Post::find($id);

        if(!$post){
            return  response()
                ->json(['message','주회할 데이터가 없습니다.'], 404);
        }
        $subject = $request->input('subject');
        $content = $request->input('content');
        $ids = $request->input('category_ids');

        if($subject) $post->subject = $subject;
        if($content) $post->content = $content;
        $post->save();
        $post->categories()->sync($ids);

        return response()->json($post);
    }


    //delete
    public function destroy(Request $request, $id){
        //Post::where('id',$id)->delete();

        $post = Post::find($id);
        if(!$post) return response()->json(['message' => '조회할 데이터가 없습니다.'], 404);

        $user = $request->user();
        if($user->id !== $post->user_id) return response()->json(['message' => '권한이 없습니다.'], 403);

        $post->delete();

        return response()->json(['message','삭제되었습니다.']);
    }

}
