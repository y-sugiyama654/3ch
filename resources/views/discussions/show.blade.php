@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.css">
@endsection

@section('content')
<div class="card mb-5">
    @include('partials.discussion-header')
    <div class="card-body">
        <div class="text-center">
            <strong>{{ $discussion->title }}</strong>
        </div>

        <hr>

        {!! $discussion->content !!}

        <hr>

        <p class="text-right">{{ $discussion->created_at->diffForHumans() }}</p>

        @if($discussion->bestReply)
            <div class="text-center" style="padding: 30px;">
                <h3 class="text-center">BEST ANSWER</h3>
                <div class="card bg-success" style="color: #fff">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <div>
                                <img width="40px" height="40px" style="border-radius: 50%" class="mr-2" src="{{ Gravatar::src($discussion->bestReply->owner->email) }}" alt="">
                                <span class="ml-2 mr-2 font-weight-bold">{{ $discussion->bestReply->owner->name }}</span><strong><i class="fas fa-donate"></i> {{ $discussion->author->point }}P</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        {!! $discussion->bestReply->content !!}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="card-footer d-flex justify-content-between">
        <div>
            <i class="fas fa-comment-dots"><span class="ml-2">{{ $discussion->replies->count() }}</span></i>
        </div>
        <div>
            <a href="{{ route('discussions.index') }}?channel={{ $discussion->channel->slug }}" class="btn btn-secondary btn-sm">{{ $discussion->channel->name }}</a>
        </div>
    </div>
</div>

@foreach($discussion->replies()->paginate(3) as $reply)
    <div class="card my-5">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <div>
                    <img height="40px" width="40px" style="border-radius: 50%" src="{{ Gravatar::src($reply->owner->email) }}" alt="">
                    <span class="ml-2 mr-2 font-weight-bold">{{ $discussion->bestReply->owner->name }}</span><strong><i class="fas fa-donate"></i> {{ $discussion->author->point }}P</strong>
                </div>
                <div>
                    @auth
                        <div class="btn-toolbar">
                            <div class="btn-group mr-1">
                                @if(!isset($discussion->reply_id))
                                    @if(auth()->user()->id === $discussion->user_id)
                                        <form action="{{ route('discussions.best-reply', ['discussion' => $discussion->slug, 'reply' => $reply->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-info">Best Anser</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                            <div class="btn-group">
                                @if(!isset($discussion->reply_id))
                                    @if(auth()->user()->id === $reply->user_id)
                                        <a href="{{ route('reply.edit', ['id' => $reply->id]) }}" class="btn btn-sm btn-info">Edit</a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
        <div class="card-body">
            {!! $reply->content !!}
        </div>
        <div class="card-footer">
            @if($reply->is_liked_by_auth_user())
                <a href="{{ route('reply.unlike', ['id' => $reply->id]) }}" class="btn btn-success btn-sm">いいね<span class="badge">{{ $reply->likes->count() }}</span></a>
            @else
                <a href="{{ route('reply.like', ['id' => $reply->id]) }}" class="btn btn-secondary btn-sm">いいね<span class="badge">{{ $reply->likes->count() }}</span></a>
            @endif
        </div>
    </div>
@endforeach
{{ $discussion->replies()->paginate(3)->links() }}

<div class="card my-5">
    <div class="card-header">
        Add a Reply
    </div>
    <div class="card-body">
        @auth
            <form action="{{ route('replies.store', $discussion->slug) }}" method="POST">
                @csrf
                <input type="hidden" name="content" id="content">
                <trix-editor input="content"></trix-editor>
                <button type="submit" class="btn btn-success btn-sm my-2">Add Reply</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="btn btn-info">Sign in to add a reply</a>
        @endauth
    </div>
</div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/1.2.1/trix.js"></script>
@endsection

