
@extends('layouts.main')

@section('title', 'Thông báo của bạn')

@section('content')
{{-- Thêm một container để nội dung được căn giữa và có khoảng cách giống trang chủ --}}
<div class="container" style="min-height: 50vh; padding-top: 30px; padding-bottom: 30px;">
    <div style="width:90%; max-width: 900px; margin:auto;">
        <h2 style="color:#e21b1b; margin-bottom:20px; font-size: 24px; border-bottom: 2px solid #f1f1f1; padding-bottom: 10px;">
            🔔 Thông báo mới nhất
        </h2>

        <ul style="list-style:none; padding:0;">
            @forelse ($notifications as $notification)
                <li style="
                    background: {{ !$notification->is_read ? '#fff' : '#f9f9f9' }};
                    border: 1px solid {{ !$notification->is_read ? '#ffcaca' : '#e0e0e0' }};
                    border-left: 5px solid {{ !$notification->is_read ? '#e21b1b' : '#ccc' }};
                    border-radius: 8px;
                    padding: 15px 20px;
                    margin-bottom: 15px;
                    opacity: {{ !$notification->is_read ? '1' : '0.8' }};
                    transition: all 0.2s ease-in-out;
                " onmouseover="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)';" onmouseout="this.style.boxShadow='none';">
                    
                    <strong style="font-size: 1.1em; color: #333;">{{ $notification->title }}</strong>
                    <p style="margin: 5px 0 10px 0; color: #555; font-weight: normal;">{{ $notification->content }}</p>

                    <small style="color:#777; font-weight: normal;">{{ $notification->created_at->diffForHumans() }}</small>
                </li>
            @empty
                <li>
                    <p>Hiện chưa có thông báo nào.</p>
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

