@extends('layouts.app') {{-- nếu bạn có layout --}}
@section('title', 'Thông báo khuyến mãi')

@section('content')
<div style="width:90%;margin:auto;padding:20px;">
    <h2 style="color:#e21b1b;margin-bottom:20px;">🔔 Thông báo khuyến mãi mới nhất</h2>

    @if($notifications->isEmpty())
        <p>Hiện chưa có thông báo nào.</p>
    @else
        <ul style="list-style:none;padding:0;">
            @foreach($notifications as $n)
                <li style="background:#fff3f3;border:1px solid #ffcaca;border-radius:8px;padding:10px 15px;margin-bottom:10px;">
                    <strong>{{ $n->email }}</strong> 
                    @if($n->phone)
                        ({{ $n->phone }})
                    @endif
                    <br>
                    <small>Đăng ký lúc: {{ $n->created_at->format('H:i d/m/Y') }}</small>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
