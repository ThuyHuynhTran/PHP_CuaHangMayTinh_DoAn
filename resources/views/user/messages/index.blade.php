@extends('layouts.main')

@section('title', 'Hộp thư của bạn')

@section('content')
<div class="container" style="min-height: 50vh; padding: 30px 0;">
    <h2 style="color:#e21b1b; border-bottom: 2px solid #f1f1f1; padding-bottom: 10px; margin-bottom: 20px;">Hộp thư của bạn</h2>
    
    @forelse($messages as $message)
        <a href="{{ route('user.messages.show', $message) }}" style="text-decoration: none; color: inherit; display: block; margin-bottom: 10px;">
            <div style="background: {{ !$message->is_read_by_user ? '#fff0f0' : '#fff' }}; border: 1px solid #ddd; padding: 15px; border-radius: 8px; {{ !$message->is_read_by_user ? 'font-weight: bold;' : '' }}">
                <p class="mb-1">Chủ đề: {{ Str::limit($message->message, 80) }}</p>
                <p style="color: #555; font-size: 0.9em; margin: 0;">Cập nhật lần cuối: {{ $message->updated_at->diffForHumans() }}</p>
                @if(!$message->is_read_by_user)
                    <span class="badge bg-danger mt-2">Tin nhắn mới</span>
                @endif
            </div>
        </a>
    @empty
        <p>Bạn chưa có cuộc hội thoại nào.</p>
    @endforelse

    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>
@endsection