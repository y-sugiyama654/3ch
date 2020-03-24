<?php

namespace App\Http\Controllers;

use App\Channel;
use Illuminate\Http\Request;

class ChannelsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    /**
     * チャンネルの一覧表示画面
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('channels.index')->with('channels', Channel::all());
    }

    /**
     * チャンネル作成画面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('channels.create');
    }

    /**
     * チャンネルの保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'channel' => 'required',
        ]);

        Channel::create([
            'name' => $request->channel,
            'slug' => str_slug($request->channel),
        ]);

        session()->flash('success', 'Channel Created');

        return redirect()->route('channels.index');
    }

    /**
     * チャンネル編集画面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        return view('channels.edit')->with('channel', Channel::find($id));
    }

    /**
     * チャンネルの編集
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $channel = Channel::find($id);

        $channel->name = $request->channel;
        $channel->save();

        session()->flash('success', 'Channel edited successfully.');

        return redirect()->route('channels.index');
    }

    /**
     * チャンネルの削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Channel::destroy($id);

        session()->flash('success', 'Channel deleted successfully.');

        return redirect()->route('channels.index');
    }
}
