@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="page-heading">
        <h3>Notifications</h3>
    </div>

    <div class="page-content">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title">All Notifications</h5>
                    <div>
                        @if($unreadCount > 0)
                            <form action="{{ route('notifikasi.markAllRead') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-secondary">Mark all read ({{ $unreadCount }})</button>
                            </form>
                        @endif
                    </div>
                </div>

                @if($notifications->isEmpty())
                    <p class="text-center text-muted">No notifications</p>
                @else
                    <ul class="list-group">
                        @foreach($notifications as $n)
                            <li class="list-group-item d-flex justify-content-between align-items-start {{ is_null($n->read_at) ? 'list-group-item-warning' : '' }}">
                                <div>
                                    <a href="{{ route('notifikasi.read', $n->id) }}" class="text-decoration-none">
                                        <div class="fw-bold">{{ $n->data['message'] }}</div>
                                        <div class="text-muted small">{{ $n->created_at->diffForHumans() }}</div>
                                    </a>
                                </div>
                                <div>
                                    @if(is_null($n->read_at))
                                        <form action="{{ route('notifikasi.read', $n->id) }}" method="GET">
                                            <button class="btn btn-sm btn-primary">Open & Mark read</button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Read</span>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif

            </div>
        </div>
    </div>
@endsection
