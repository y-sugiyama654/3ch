@section('css')
    <link href="{{ asset('css/discussion-header.css') }}" rel="stylesheet">

@endsection
<div class="card-header">

    @if($discussion->getBestReply())
        {{--  CLOSE  --}}
        <div class="d-flex justify-content-between">
            <div class="ribbon18-content">
                <span class="ribbon18">CLOSED</span>
            </div>
            <div>
                <img width="40px" height="40px" style="border-radius: 50%" src="{{ Gravatar::src($discussion->author->email) }}" alt="">
                <span class="ml-2 mr-2 font-weight-bold">{{ $discussion->author->name }}</span><strong><i class="fas fa-donate"></i> {{ $discussion->author->point }}P</strong>
            </div>
            <div>
                @if (request()->path() == 'discussions')
                    <a href="{{ route('discussions.show', $discussion->slug) }}" class="btn btn-success btn-sm mr-5">View</a>
                @else
                    @if (Auth::id() == $discussion->user_id)
                        <a href="{{ route('discussions.edit', $discussion->slug) }}" class="btn btn-success btn-sm">Edit</a>
                    @endif
                    @if ($discussion->is_being_watched_by_auth_user())
                        <a href="{{ route('discussions.unwatch', $discussion->id) }}" class="btn btn-success btn-sm mr-5">Unwatch</a>
                    @else
                        <a href="{{ route('discussions.watch', $discussion->id) }}" class="btn btn-success btn-sm mr-5">Watch</a>
                    @endif
                @endif
            </div>
        </div>
    @else
        {{--  OPEN  --}}
        <div class="d-flex justify-content-between">
            <div>
                <img width="40px" height="40px" style="border-radius: 50%" src="{{ Gravatar::src($discussion->author->email) }}" alt="">
                <span class="ml-2 mr-2 font-weight-bold">{{ $discussion->author->name }}</span><strong><i class="fas fa-donate"></i> {{ $discussion->author->point }}P</strong>
            </div>
            <div>
                @if (request()->path() == 'discussions')
                    <a href="{{ route('discussions.show', $discussion->slug) }}" class="btn btn-success btn-sm">View</a>
                @else
                    @if (Auth::id() == $discussion->user_id)
                        <a href="{{ route('discussions.edit', $discussion->slug) }}" class="btn btn-success btn-sm">Edit</a>
                    @endif
                    @if ($discussion->is_being_watched_by_auth_user())
                        <a href="{{ route('discussions.unwatch', $discussion->id) }}" class="btn btn-success btn-sm">Unwatch</a>
                    @else
                        <a href="{{ route('discussions.watch', $discussion->id) }}" class="btn btn-success btn-sm">Watch</a>
                    @endif
                @endif
            </div>
        </div>
    @endif

</div>
