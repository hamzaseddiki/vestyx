@section('style')
    <link rel="stylesheet" href="{{global_asset('assets/common/css/toastr.css')}}">
@endsection

<section class="faqArea-global section-padding2">
    <div class="container" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                <div class="section-tittle text-center mb-50">

                    {!! get_modified_title($data['heading_title']) !!}
                </div>
            </div>
        </div>
        <div class="row">
            @php
                $parent_rand_number = \Illuminate\Support\Str::random(20);
             use Illuminate\Support\Str;
            @endphp
            <div class="col-xl-12">
                <div class="collapse-wrapper">
                    <div class="accordion" id="accordionExample-{{$parent_rand_number}}">
                        <div class="row ">
                            <div class="col-xl-4 col-lg-6">
                                @php
                                    $all_cat = $data['categories'];
                                    $categories_keys = array_keys($all_cat->toArray());
                                @endphp
                                <!-- Tab Menu -->
                                <div class="tab-menu-global tabStyleTwo mb-20">
                                    <ul>
                                        @foreach($data['categories'] ?? [] as $cat)
                                         <li><a href="#" class="{{$loop->first == 'first' ? 'active' : ''}}" data-rel="tab-{{$loop->iteration}}">{{$cat->getTranslation('title',get_user_lang())}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <form class="input-form input-form2 mb-30" action="{{route('tenant.faq.quistion.mail.send')}}" method="post" id="faq_form">
                                    <h5 class="mb-20">{{ $data['left_title'] }}</h5>
                                    <input type="email" class="form-control" name="email" placeholder="Enter your email">
                                    <textarea name="message" placeholder="Write here your Question" id="message"></textarea>
                                    <div class="btn-wrapper">
                                        <button type="button" class="cmn-btn-outline3 faq_submit_button">{{$data['button_text']}}</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-7 col-lg-6">
                                <!-- Tab Items 1 -->
                                @foreach($data['categories'] ?? [] as $cat)
                                    <!-- Tab Items 1 -->
                                    <div class="singleTab-items-global" id="tab-{{$loop->iteration}}" {{ $loop->iteration == 1 ? 'style=display:block;' : "" }}>
                                        @foreach($data['faq']->where("category_id", $cat->id) ?? [] as $fq)
                                            @php
                                                $child_rand = \Illuminate\Support\Str::random(15);
                                            @endphp
                                            <!-- Single 01 -->
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button {{ $loop->index == 0 ? "" : "collapsed" }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-{{$child_rand}}" aria-expanded="{{$loop->index == 0 ? 'true' : 'false'}}" aria-controls="collapseOne-{{$child_rand}}">
                                                        {{$fq->getTranslation('title',get_user_lang())}}
                                                    </button>
                                                </h2>
                                                <div id="collapseOne-{{$child_rand}}" class="accordion-collapse collapse {{$loop->first ? 'show' : ''}}" aria-labelledby="headingOne" data-bs-parent="#accordionExample-{{$parent_rand_number}}">
                                                    <div class="accordion-body">
                                                        <p>{!!  Str::words($fq->getTranslation('description',get_user_lang()),110) !!}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                                <!-- end tab -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


@section('scripts')
    <script src="{{global_asset('assets/common/js/toastr.min.js')}}"></script>
    <script>
        $(document).ready(function(){

            $(document).on('click','.faq_submit_button',function(e){
                e.preventDefault();
                el = $(this);

                let form = $('#faq_form');
                let user_email = $('input[type="email"]').val();
                let user_message = $('#message').val();

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: {
                        email : user_email,
                        message : user_message,
                        _token: '{{csrf_token()}}'
                    },

                    beforeSend: function (){
                        el.text('Submiting..')
                    },

                    success: function(data){
                        el.text('Submit')
                        $('input[type="email"]').val('');
                        $('#message').val('');
                        toastr.success(data.msg);
                    },
                    error: function(error){
                       let errors = error.responseJSON.errors;

                        $.each(errors,function(key,val){
                            el.text('Submit')
                           toastr.error(val)
                       })

                    }

                });
            })

        });
    </script>
@endsection
