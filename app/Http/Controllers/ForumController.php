<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Forum;
use App\Models\Replies;

class ForumController extends Controller
{

    public function viewCategory($category_id)
    {
        $this->validateCategoryId($category_id);

        $posts = Forum::where('category', $category_id)
            ->orderBy('pinned', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->with('user')
            ->withCount('replies') 
            ->paginate(5);

        $categoryNames = $this->getCategoryNames();
        $category_name = $categoryNames[$category_id] ?? '406';

        return view('forum.view-category', compact('posts', 'category_name', 'category_id'));
    }

    public function viewPost($post_id)
    {
        $post = Forum::with('user', 'replies.user')->findOrFail($post_id);
    
        $user = $post->user;
    
        $postCount = $user->postCount();
    
        $categoryNames = $this->getCategoryNames();
        $category_name = $categoryNames[$post->category] ?? '406';
    
        return view('forum.view-post', [
            'post' => $post,
            'replies' => $post->replies()->with('user')->paginate(5),
            'category_name' => $category_name,
            'category_id' => $post->category,
            'postCount' => $postCount,
            'repliesWithPostCount' => $post->replies->map(function ($reply) {
                return [
                    'id' => $reply->id,
                    'userPostCount' => $reply->user->postCount(),
                    'reply' => $reply->reply,
                    'banned' => $reply->banned,
                    'posterId' => $reply->posterId,
                    'created_at' => $reply->created_at,
                    'posterUsername' => $reply->user->name,
                ];
            }),
        ]);
    }
    
    public function index() {
        $general = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 1)->first();
        $general_post_count = Forum::where('category', 1)->count();

        $off_topic = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 2)->first();
        $off_topic_post_count = Forum::where('category', 2)->count();

        $dev = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 3)->first();
        $dev_post_count = Forum::where('category', 3)->count();

        $help = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 4)->first();
        $help_post_count = Forum::where('category', 4)->count();

        $politics = Forum::orderBy('created_at', 'DESC')->with('user')->where('category', 5)->first();
        $politics_post_count = Forum::where('category', 5)->count();

        return view('forum', compact('general', 'general_post_count', 'off_topic', 'off_topic_post_count', 'dev', 'dev_post_count', 'help', 'help_post_count', 'politics', 'politics_post_count'));

    }

    public function replyToPost($post_id)
    {
        $post = Forum::findOrFail($post_id);
        return view('forum.reply', compact('post'));
    }

    public function newPost($category_id)
    {
        return view('forum.new-post', compact('category_id'));
    }

    public function createPost($category_id, Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:50'],
            'body' => ['required', 'string', 'max:255'],
        ]);

        Forum::create([
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'posterId' => Auth::id(),
            'category' => $category_id,
        ]);

        return redirect('/app/forum/' . $category_id);
    }

    public function createReply($post_id, Request $request)
    {
        $validated = $request->validate([
            'reply' => ['required', 'string', 'max:500'],
        ]);

        Replies::create([
            'reply' => $validated['reply'],
            'posterId' => Auth::id(),
            'replyPostId' => $post_id,
        ]);

        return redirect('/app/forum/view/' . $post_id);
    }

    private function validateCategoryId($category_id)
    {
        if ($category_id < 1 || $category_id > 5) {
            abort(404);
        }
    }

    private function getCategoryNames()
    {
        return [
            1 => 'General',
            2 => 'Off-Topic',
            3 => 'Development/Scripting',
            4 => 'Help',
            5 => 'Politics',
        ];
    }
}