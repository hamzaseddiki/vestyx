<section class="categoriesArea" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <a href="{{$data['image_url']}}">
                    {!! render_image_markup_by_attachment_id($data['image'],'','full') !!}
                </a>

            </div>
        </div>
    </div>
</section>
