<!-- resources/views/admin/messages/_conversation.blade.php -->
@foreach($message->conversation as $chat)
    @if($chat['sender'] == 'admin')
        <!-- Tin nhắn của Admin (bên phải) -->
        <div class="d-flex justify-content-end mb-3">
            <div class="p-3 rounded bg-danger text-white" style="max-width: 70%;">
                <p class="fw-bold mb-1">Bạn (Admin)</p>
                <p class="mb-1">{!! nl2br(e($chat['content'])) !!}</p>
                <small class="text-white-50">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('d/m/Y H:i') }}</small>
            </div>
        </div>
    @else
        <!-- Tin nhắn của User (bên trái) -->
        <div class="d-flex justify-content-start mb-3">
            <div class="p-3 rounded bg-light border" style="max-width: 70%;">
                <p class="fw-bold mb-1">{{ $message->name }}</p>
                <p class="mb-1">{!! nl2br(e($chat['content'] ?? '')) !!}</p>
                <small class="text-muted">{{ \Carbon\Carbon::parse($chat['timestamp'])->format('d/m/Y H:i') }}</small>
            </div>
        </div>
    @endif
@endforeach
