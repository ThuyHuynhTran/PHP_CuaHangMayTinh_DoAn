@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="text-center mb-4">💬 Câu hỏi thường gặp (FAQ)</h2>

    @foreach($faqs as $faq)
        <div class="faq-item mb-3">
            <button class="faq-question">{{ $faq->question }}</button>
            <div class="faq-answer">
                <p>{{ $faq->answer }}</p>
            </div>
        </div>
    @endforeach
</div>

<style>
.faq-item {
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}
.faq-question {
    width: 100%;
    text-align: left;
    background: #f9f9f9;
    border: none;
    font-weight: 600;
    padding: 12px 16px;
    cursor: pointer;
}
.faq-question:hover {
    background: #eee;
}
.faq-answer {
    display: none;
    padding: 12px 16px;
    background: #fff;
}
.faq-answer p {
    margin: 0;
}
</style>

<script>
document.querySelectorAll('.faq-question').forEach(btn => {
    btn.addEventListener('click', function() {
        const answer = this.nextElementSibling;
        answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
    });
});
</script>
@endsection
