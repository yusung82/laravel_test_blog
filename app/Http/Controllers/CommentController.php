<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function create(Request $request, $post_id){
        $post = Post::find($post_id);

        if(!$post){
            return abort(404);
        }
        $user = $request->user();

        $content = $request->input('content');
        $author = $request->input('author');

        $comment = new Comment();
        $comment->user_id = $user->id;
        $comment->post_id = $post_id;
        $comment->content = $content;
        $comment->save();

        return response()->json($comment);
    }


    public function destroy(Request $request, $post_id, $id){
        $comment = Comment::where('post_id', $post_id)->where('id',$id)->first();

        if(!$comment) abort(404);

        $user = $request->user();
        if( $user->id !== $comment->user_id){
            return  abort(403);
        }

        $comment->delete();

        return response()->json(['message', '메시지가 삭제되었습니다.']);
    }

}
