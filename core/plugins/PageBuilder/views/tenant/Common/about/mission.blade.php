
<div class="missionArea">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-9">
                <div class="section-tittle text-center mb-50">
                    <h2 class="tittle">{{$data['title']}}</h2>
                    <p class="mb-50">{{$data['description']}}</p>
                    <div class="imgEffect overflow-hidden">
                        {!! render_image_markup_by_attachment_id($data['image']) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
