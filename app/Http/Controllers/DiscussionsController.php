<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscussionRequest;
use App\Reply;
use Illuminate\Http\Request;
use App\Discussion;

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
        $discussions = Discussion::filterByChannels()->paginate(5);

        foreach($discussions as $discussion) {
            $formatDate = $this->_convert_to_fuzzy_time($discussion->created_at->format('Y-m-d'));
        }

        return view('discussions.index', [
            'discussions' => Discussion::filterByChannels()->paginate(5),
            'formatDate' => $formatDate
        ]);
    }

    /**
     * X秒前、X分前、X時間前、X日前などといった表示に変換する。
     * 一分未満は秒、一時間未満は分、一日未満は時間、
     * 31日以内はX日前、それ以上はX月X日と返す。
     * X月X日表記の時、年が異なる場合はyyyy年m月d日と、年も表示する
     *
     * @param string $createdAt       strtotime()で変換できる時間文字列 (例：yyyy/mm/dd H:i:s)
     * @return string X日前,などといった文字列
     **/
    private function _convert_to_fuzzy_time($createdAt)
    {
        $unix   = strtotime($createdAt);
        $now    = time();
        $diff_sec   = $now - $unix;

        if($diff_sec < 60){
            $time   = $diff_sec;
            $unit   = "秒前";
        }
        elseif($diff_sec < 3600){
            $time   = $diff_sec/60;
            $unit   = "分前";
        }
        elseif($diff_sec < 86400){
            $time   = $diff_sec/3600;
            $unit   = "時間前";
        }
        elseif($diff_sec < 2764800){
            $time   = $diff_sec/86400;
            $unit   = "日前";
        }
        else{
            if(date("Y") != date("Y", $unix)){
                $time   = date("Y年n月j日", $unix);
            }
            else{
                $time   = date("n月j日", $unix);
            }

            return $time;
        }

        return (int)$time .$unit;
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
        auth()->user()->discussion()->create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'content' => $request['content'],
            'channel_id' => $request->channel
        ]);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
