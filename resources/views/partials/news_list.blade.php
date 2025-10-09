@foreach($news as $item)
    <div class="news-item" style="background:white; border-radius:10px; overflow:hidden;
        box-shadow:0 4px 15px rgba(0,0,0,0.1); transition:transform 0.3s;">
        <img src="{{ asset('assets/img/news/' . $item['image']) }}" 
             alt="{{ $item['title'] }}"
             style="width:100%; height:160px; object-fit:cover;">
        <div style="padding:15px;">
            <h3 style="font-size:16px; line-height:1.4; color:#333;">
                {{ $item['title'] }}
            </h3>
        </div>
    </div>
@endforeach
