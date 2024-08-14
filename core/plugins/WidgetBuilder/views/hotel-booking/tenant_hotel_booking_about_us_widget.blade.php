<div class="footer-contents-logo">
    <a href="{{url('/')}}" class="footer-contents-logo-img">
        {!! render_image_markup_by_attachment_id($data['logo_image'] ?? 0) !!}
    </a>
    <h3 class="footer-contents-logo-title text-white">
        <a href="{{url('/')}}"> {{$data['title'] ?? ''}} </a>
    </h3>
    <p class="footer-contents-logo-para mt-3">
        {!! $data['description'] ?? '' !!}
    </p>
</div>
