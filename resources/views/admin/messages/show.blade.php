@extends('layouts.admin')

@section('title', 'Chi tiết tin nhắn')

@section('content')
<div class="container-fluid px-4">
    <h2 class="mb-4">
        <i class="fas fa-comments text-danger"></i> Cuộc hội thoại
    </h2>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Hội thoại với {{ $message->name }} ({{ $message->email }})</h5>
        </div>
        
        {{-- Khung chat --}}
        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
            <!-- Tin nhắn gốc của người dùng -->
            <div class="d-flex justify-content-start mb-3">
                <div class="p-3 rounded bg-light border" style="max-width: 70%;">
                    <p class="fw-bold mb-1">{{ $message->name }}</p>
                    <p class="mb-1">{!! nl2br(e($message->message)) !!}</p>
                    <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                </div>
            </div>

            <!-- Lịch sử hội thoại -->
            @if($message->conversation)
                @foreach($message->conversation as $chat)
                    @if(isset($chat['sender']) && $chat['sender'] == 'admin')
                        <!-- Tin nhắn của Admin (bên phải) -->
                        <div class="d-flex justify-content-end mb-3">
                            <div class="p-3 rounded bg-danger text-white" style="max-width: 70%;">
                                <p class="fw-bold mb-1">Bạn (Admin)</p>
                                <p class="mb-1">{!! nl2br(e($chat['content'])) !!}</p>
                                @if(isset($chat['timestamp']))
                                <small class="text-white-50">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('d/m/Y H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Tin nhắn của User (bên trái) -->
                        <div class="d-flex justify-content-start mb-3">
                            <div class="p-3 rounded bg-light border" style="max-width: 70%;">
                                <p class="fw-bold mb-1">{{ $message->name }}</p>
                                <p class="mb-1">{!! nl2br(e($chat['content'] ?? '')) !!}</p>
                                 @if(isset($chat['timestamp']))
                                <small class="text-muted">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('d/m/Y H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>

        {{-- Form nhập phản hồi --}}
        <div class="card-footer bg-light">
            <form method="POST" action="{{ route('admin.messages.update', $message->id) }}">
                @csrf
                @method('PUT')
                <div class="input-group">
                    <textarea name="admin_reply" class="form-control" placeholder="Nhập phản hồi của bạn..." rows="3" required></textarea>
                    <button class="btn btn-danger" type="submit">
                        <i class="fas fa-paper-plane"></i> Gửi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại Hộp thư
        </a>
    </div>
</div>
@endsection
