<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscussionRequest;
use App\Reply;
use Illuminate\Http\Request;
use App\Discussion;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class DiscussionsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        switch (request('filter')) {
            case 'me':
                $results = Discussion::where('user_id', Auth::id())->orderBy('created_at', 'desc')->paginate(5);
                break;
            case 'solved':
                $answered = [];

                foreach(Discussion::all() as $discussion) {
                    if ($discussion->getBestReply()) {
                        array_push($answered, $discussion);
                    }
                }

                $results = new Paginator($answered, 5);
                break;
            case 'unsolved':
                $unanswered = [];

                foreach(Discussion::all() as $discussion) {
                    if (!$discussion->getBestReply()) {
                        array_push($unanswered, $discussion);
                    }
                }

                $results = new Paginator($unanswered, 5);
                break;
            default:
                $results = Discussion::orderBy('created_at', 'desc')->paginate(5);
                break;
        }

        return view('discussions.index', ['discussions' => $results]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('discussions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateDiscussionRequest $request)
    {
        $discussion = auth()->user()->discussion()->create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'content' => $request['content'],
            'channel_id' => $request->channel
        ]);

        // slugがnullの場合はdiscussion-XXを代わりに代入する(XXがdiscussionのid)
        if (empty($discussion->slug)) {
            $discussionId = $discussion->id;
            $discussion->slug = 'discussion-' . $discussionId;
            $discussion->save();
        }

        session()->flash('success', 'Discussion posted.');

        return redirect(route('discussions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param Discussion $discussion
     * @return void
     */
    public function show(Discussion $discussion)
    {
        return view('discussions.show', [
           'discussion' => $discussion
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        return view('discussions.edit', ['discussion' => Discussion::where('slug', $slug)->first()]);
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
        $this->validate($request, [
            'content' => 'required',
        ]);

        $discussion = Discussion::find($id);

        $discussion->content = $request->content;

        $discussion->save();

        session()->flash('success', 'Discussion Updated');

        return redirect()->route('discussions.show', ['discussion' => $discussion]);
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

    public function reply(Discussion $discussion, Reply $reply)
    {
        $discussion->markAsBestReply($reply);

        session()->flash('success', 'Mark as Best Reply.');

        return redirect()->back();
    }
}
