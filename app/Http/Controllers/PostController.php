<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Comment;
use App\Post;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client = new Client([
            'base_uri' => 'https://jsonplaceholder.typicode.com',
            'timeout'  => 2.0,
        ]);

        $GetPosts = $client->request('GET', 'posts');
        $GetUsers = $client->request('GET', 'users');

        $posts = json_decode($GetPosts->getBody()->getContents());
        $users = json_decode($GetUsers->getBody()->getContents());

        foreach ($users as $user) {
            $exists = User::where('id', $user->id)->exists();
            if ($exists == false) {
                User::insert([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'address' => $user->address->street . ' - ' . $user->address->suite . ' - ' . $user->address->city,
                    'password' => Hash::make('menitaTrial'),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {
                User::where('id', '=', $user->id)->update([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'address' => $user->address->street . ' - ' . $user->address->suite . ' - ' . $user->address->city,
                    'updated_at' => Carbon::now()
                ]);
            }
        }

        foreach ($posts as $post) {
            $exists = Post::where('userId', '=', $post->userId)->where('title', '=', $post->title)->exists();

            if ($exists == false) {
                Post::insert([
                    'userId' => $post->userId,
                    'title' => $post->title,
                    'body' => $post->body,
                    'is_published' => '1',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {
                Post::where('id', '=', $post->id)->where('userId', '=', $post->userId)->insert([
                    'userId' => $post->userId,
                    'title' => $post->title,
                    'body' => $post->body,
                    'is_published' => '1',
                    'updated_at' => Carbon::now()
                ]);
            }
        }


        $articles = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.created_at', 'users.name')
            ->leftjoin('users', 'users.id', '=', 'posts.userId')->get();

        return view('posts.index', ['posts' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Comment::insert([
            'userId' => $request->userId,
            'comment_body' => $request->comment,
            'comment_status' => '1',
            'articleid' => $request->idArticle,
            'created_at' => Carbon::now()
        ]);

      return redirect ('/post-detail/'. $request->idArticle. '/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $client = new Client([
            'base_uri' => 'https://jsonplaceholder.typicode.com',
            'timeout'  => 2.0,
        ]);

        $article = DB::table('posts')->select()->leftjoin('users', 'users.id', '=', 'posts.userId')
            ->where('posts.id', '=', $id)->get();

        $idarticle = $id;

         $getComments = $client->request('GET', 'posts/'.$id.'/comments');

         $commentsExternal = json_decode($getComments->getBody()->getContents());

         $commentsInternal = DB::table('comments')->select()->leftjoin('users', 'users.id', '=', 'comments.userId')->where('articleid', '=', $id)->where('comment_status', '=', '1')->get();

        return view('posts.show', [
            'article' => $article,
            'commentsExternal' => $commentsExternal,
            'commentsInternal' => $commentsInternal,
            'idArticle' => $idarticle
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $idArticle)
    {
        $client = new Client([
            'base_uri' => 'https://jsonplaceholder.typicode.com',
            'timeout'  => 2.0,
        ]);

        $article = DB::table('posts')->select()->leftjoin('users', 'users.id', '=', 'posts.userId')
            ->where('posts.id', '=', $idArticle)->get();



         $getComments = $client->request('GET', 'posts/'.$idArticle.'/comments');

         $commentsExternal = json_decode($getComments->getBody()->getContents());

         $commentsInternal = DB::table('comments')->select()->leftjoin('users', 'users.id', '=', 'comments.userId')->where('articleid', '=', $idArticle)->where('comment_status', '=', '1')->get();

        $comment = DB::table('comments')->select('comment_body')->where('id', '=', $id)->where('articleId', '=', $idArticle)->first();


        $commentString = $comment->comment_body;

        return view('posts.edit',[
            'commentID' => $id,
            'commentString' => $commentString,
            'article' => $article,
            'commentsExternal' => $commentsExternal,
            'commentsInternal' => $commentsInternal,
            'idArticle' => $idArticle
        ]);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        Comment::where('id', $request->idcomment)->where('articleId', '=', $request->idArticle)->update([
            'comment_body' => $request->comment,
            'updated_at' => Carbon::now()
        ]);

      return redirect ('/post-detail/'. $request->idArticle. '/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $idArticle)
    {
        Comment::where('id', $id)->where('articleId', '=', $idArticle)->update([
            'comment_status' => '0',
            'updated_at' => Carbon::now()
        ]);

      return redirect ('/post-detail/'. $idArticle. '/');
    }
}
