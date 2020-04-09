<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\Http\Requests\CreateDiscussionRequest;
use App\Http\Requests\CreateReplyRequest;
use App\Like;
use App\Reply;
use Notification;
use App\Notifications\NewReplyAdded;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RepliesController extends Controller
{
    /** リプライ時の加算ポイント
     */
    const REPLY_POINT = 25;

    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['like', 'unlike']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(CreateReplyRequest $request, Discussion $discussion)
    {
        $reply = auth()->user()->replies()->create([
            'content' => $request->content,
            'discussion_id' => $discussion->id,
        ]);

        $this->_addExperiencePoint($reply);

        // ディスカッションの投稿者に通知メッセージを送信
        if ($discussion->author->id != auth()->user()->id) {
            $discussion->author->notify(new NewReplyAdded($discussion));
        }

        // ディスカッションのウォッチャーに通知メッセージを送信
        $watchers = array();

        foreach($discussion->watchers as $watcher) {
            array_push($watchers, User::find($watcher->user_id));
        }

        Notification::send($watchers, new NewReplyAdded($discussion));

        session()->flash('success', 'Reply Added.');

        return redirect()->back();
    }

    private function _addExperiencePoint($reply)
    {
        $reply->owner->point += self::REPLY_POINT;
        $reply->owner->save();
    }

    /**
     * 引数のIDに紐づくリプライにLIKEする
     *
     * @param $id リプライID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like($id)
    {
        Like::create([
            'reply_id' => $id,
            'user_id' => Auth::id(),
        ]);

        session()->flash('success', 'You Liked the Reply.');

        return redirect()->back();
    }

    /**
     * 引数のIDに紐づくリプライにUNLIKEする
     *
     * @param $id リプライID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unlike($id)
    {
        $like = Like::where('reply_id', $id)->where('user_id', Auth::id())->first();

        $like->delete();

        session()->flash('success', 'You Unliked the Reply.');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('replies.edit', ['reply' => Reply::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
           'content' => 'required',
        ]);

        $reply = Reply::find($id);

        $reply->content = request()->content;

        $reply->save();

        session()->flash('success', 'Reply Updated.');

        return redirect()->route('discussions.show', ['discussion' => $reply->discussion]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
