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
    <div class="card-footer">
        <div class="">
            <i class="fas fa-comment-dots"><span class="ml-2">{{ $discussion->replies->count() }}</span></i>
        </div>
    </div>
</div>
@endforeach

{{ $discussions->appends(['channel' => request()->query('channel')])->links() }}

@endsection
