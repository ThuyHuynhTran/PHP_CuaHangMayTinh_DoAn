@extends('layouts.main')

@section('title', 'Chi tiết tin nhắn')

@section('content')
<div class="container" style="padding: 30px 0;">
    <div class="card shadow-sm" style="max-width: 800px; margin: auto;">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Cuộc hội thoại với Hỗ trợ viên</h5>
        </div>
        
        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            {{-- Tin nhắn gốc của người dùng --}}
            <div class="d-flex justify-content-end mb-3">
                <div class="p-3 rounded bg-primary text-white" style="max-width: 70%;">
                    <p class="fw-bold mb-1">Bạn</p>
                    <p class="mb-1">{!! nl2br(e($message->message)) !!}</p>
                    <small class="text-white-50">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>
            
            {{-- Lịch sử hội thoại --}}
            @if($message->conversation)
                @foreach($message->conversation as $chat)
                    @if(isset($chat['sender']) && $chat['sender'] == 'user')
                        <div class="d-flex justify-content-end mb-3">
                            <div class="p-3 rounded bg-primary text-white" style="max-width: 70%;">
                                 <p class="fw-bold mb-1">Bạn</p>
                                 <p class="mb-1">{!! nl2br(e($chat['content'])) !!}</p>
                                 @if(isset($chat['timestamp']))<small class="text-white-50">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('d/m/Y H:i') }}</small>@endif
                            </div>
                        </div>
                    @else
                        <div class="d-flex justify-content-start mb-3">
                            <div class="p-3 rounded bg-light border" style="max-width: 70%;">
                                 <p class="fw-bold mb-1">Hỗ trợ viên</p>
                                 <p class="mb-1">{!! nl2br(e($chat['content'] ?? '')) !!}</p>
                                 @if(isset($chat['timestamp']))<small class="text-muted">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('d/m/Y H:i') }}</small>@endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        <div class="card-footer bg-light">
            <form method="POST" action="{{ route('user.messages.reply', $message->id) }}">
                @csrf
                <div class="input-group">
                    <textarea name="user_reply" class="form-control" placeholder="Nhập tin nhắn của bạn..." rows="3" required></textarea>
                    <button class="btn btn-danger" type="submit"><i class="fas fa-paper-plane"></i> Gửi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection