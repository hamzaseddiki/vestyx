@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/magnific-popup.css')}}">
    <script src="{{global_asset('assets/tenant/frontend/themes/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{global_asset('assets/tenant/frontend/themes/js/main.js')}}"></script>
@endsection

<div class="galleryArea">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row">
            <div class="col-lg-12"></div>
        </div>
    </div>
</div>
<!-- galleryArea S t a r t-->
<section class="galleryArea-global section-padding2">
    <div class="container">

        <div class="row">
            <div class="col-xl-12">
                <div class="row ">
                    <div class="col-xl-12">

                        <!-- Tab Menu -->
                        <div class="tab-menu-global tabStyleThree text-center mb-20">
                            <ul>
                                @foreach($data['categories'] ?? [] as $cat)
                                   <li><a href="#" class="{{$loop->first ? 'active' : ''}}" data-rel="tab-{{$loop->iteration}}">{{$cat->getTranslation('title',get_user_lang())}}</a></li>
                                @endforeach
                            </ul>
                        </div>

                    </div>
                    <div class="col-xl-12">
                        @foreach($data['categories'] ?? [] as $key=> $catagory)
                        <div class="singleTab-items-global" id="tab-{{$loop->iteration}}" {{$loop->iteration == 1 ? 'style=display:block' : ''}} >
                            <div class="row">
                              @foreach($data['gallery']->where("category_id", $catagory->id) ?? [] as $gallery)
                                <div class="col-lg-4">
                                    <div class="row">
                                        @php
                                            $title = $gallery->getTranslation('title',get_user_lang());
                                            $splited = str_split($title,12) ?? '';

                                             $gallery_img = get_attachment_image_by_id($gallery->image,'full',false);
                                             $img_url = $gallery_img['img_url'] ?? '';

                                        @endphp
                                        <div class="col-lg-12 col-md-6">
                                            <figure class="singleGallery-global areaHeight1">
                                                <div class="imgHeight1">
                                                   {!! render_image_markup_by_attachment_id($gallery->image) !!}

                                                </div>

                                                <figcaption class="caption">
                                                    <h2>
                                                        <a href="{{$img_url}}" title="{{$gallery->getTranslation('title',get_user_lang())}}" class="image-popup">
                                                            {{$splited[0] ?? ''}} <span>{{$splited[1] ?? ''}}</span>
                                                        </a>
                                                    </h2>

                                                    <p>{{$gallery->getTranslation('subtitle',get_user_lang())}}</p>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                               @endforeach
                            </div>
                        </div>
                         @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@section('scripts')
    <script src="{{global_asset('assets/common/js/jquery.magnific-popup.js')}}"></script>

    <script>
        $('.image-popup').magnificPopup({
            type: 'image',
            gallery:{
                enabled:true
            }
        });
    </script>
@endsection
