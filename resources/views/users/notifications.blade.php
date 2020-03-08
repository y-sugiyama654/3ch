@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Notifications</div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($notifications as $notification)
                    <li class="list-group-item">
                        @if($notification->type === 'App\Notifications\NewReplyAdded')
                            <span>A New Reply Was Added to Your Discussion.</span>
                            <span style="font-weight: bold;">{{ $notification->data['discussion']['title'] }}</span>
                            <a href="{{ route('discussions.show', $notification->data['discussion']['slug']) }}" class="btn btn-sm btn-info float-right">View Discussion</a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
