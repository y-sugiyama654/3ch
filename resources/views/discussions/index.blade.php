@extends('layouts.app')

@section('content')

@foreach($discussions as $discussion)
<div class="card mb-5">
    @include('partials.discussion-header')
    <div class="card-body">
        <div class="text-center">
            <strong>{!! $discussion->title !!}</strong>
        </div>
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
@endforeach

{{ $discussions->appends(['channel' => request()->query('channel')])->links() }}

@endsection
