@php
    $route_name = is_null(tenant()) ? 'landlord' : 'tenant';
@endphp
@extends($route_name.'.admin.admin-master')
@section('title')
    {{__('Edit Words Settings')}}
@endsection
@section('site-title')
    {{__('Edit Words Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp">
                            <h4 class="header-title">
                                {{__("Change All Words")}}
                            </h4>
                            <div class="header-title">

                                <a class="btn btn-secondary btn-sm margin-bottom-30 mr-1" href="{{ route(route_prefix().'admin.languages')}}">  <i class="fa fa-backward" aria-hidden="true"></i> {{__('All Languages')}}</a>
                                <a href="#" id="regenerate_source_text_btn" class="btn btn-warning margin-bottom-30 btn-sm">{{__('Regenerate Source Texts')}}</a>
                                <button class="btn btn-info btn-sm margin-bottom-30 add_new_string_btn"  data-bs-toggle="modal" data-bs-target="#add_new_string_modal"> <i class="las la-plus mr-1"></i> {{__('Add New String')}}</button>
                            </div>
                        </div>
                        <p class="text-info margin-bottom-20">{{__('select any source text to translate it, then enter your translated text in textarea hit update')}}</p>
                        <div class="language-word-translate-box">
                            <div class="search-box-wrapper">
                                <input type="text" name="word_search" id="word_search" placeholder="{{__('Search Source Text...')}}">
                            </div>
                            <div class="top-part">
                                <div class="single-string-wrap">
                                    <div class="string-part">{{__('Source Text')}}</div>
                                    <div class="translated-part">{{__('Translation')}}</div>
                                </div>
                            </div>
                            <div class="middle-part">
                                @foreach($all_word as $key => $value)
                                    <div class="single-string-wrap">
                                        <div class="string-part" data-key="{{$key}}">{{$key}}</div>
                                        <div class="translated-part" data-trans="{{$value}}">{{$key === $value ? '' : $value}}</div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="footer-part">
                                <h6 id="selected_source_text"><span>{{__('Source Text:')}}</span> <strong class="text"></strong></h6>
                                <form action="{{route(route_prefix().'admin.languages.words.update',$lang_slug)}}" method="POST" id="langauge_translate_form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="type" value="{{$type}}">
                                    <input type="hidden" name="string_key">
                                    <div class="from-group">
                                        <label for="">{{__('Translate To')}} <strong>{{$language->name}}</strong></label>
                                        <textarea name="translate_word" cols="30" rows="5" class="form-control" placeholder="{{__('enter your translate words')}}"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">{{__('Update Changes')}}</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_new_string_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add New Translate String')}}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route(route_prefix().'admin.languages.add.string')}}" id="add_new_string_modal_form"  method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="slug" value="{{$lang_slug}}">
                        <input type="hidden" name="type" value="{{$type}}">
                        <div class="form-group">
                            <label for="string">{{__('String')}}</label>
                            <input type="text" class="form-control" name="string" placeholder="{{__('String')}}">
                        </div>
                        <div class="form-group">
                            <label for="translate_string">{{__('Translated String')}}</label>
                            <input type="text" class="form-control" name="translate_string" placeholder="{{__('Translated String')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        (function($){
            "use strict";

            $(document).ready(function (){
                $(document).on('click','.language-word-translate-box .middle-part .single-string-wrap .string-part',function (e){
                    e.preventDefault();
                    let langKey = $(this).data('key');
                    let langValue = $(this).next().data('trans');
                    let formContainer = $('#langauge_translate_form');
                    $('#selected_source_text strong').text(langKey);
                    formContainer.find('input[name="string_key"]').val(langKey);
                    formContainer.find('textarea[name="translate_word"]').val(langValue);
                });
                //search source text
                $(document).on('keyup','#word_search',function (e){
                    e.preventDefault();
                    let searchText = $(this).val();
                    var allSourceText = $('.language-word-translate-box .middle-part .single-string-wrap .string-part');
                    $.each(allSourceText,function (index,value){
                        var text = $(this).text();
                        var found = text.toLowerCase().match(searchText.toLowerCase().trim());
                        if (!found){
                            $(this).parent().hide();
                        }else{
                            $(this).parent().show();
                        }
                    });
                });

                $(document).on('click','#regenerate_source_text_btn',function (e){
                    e.preventDefault();
                    //admin.languages.regenerate.source.texts
                    Swal.fire({
                        title: '{{__("Are you sure?")}}',
                        text: '{{__("It will delete current source texts, you will lose your current translated data!")}}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        closeDuration : 300,
                        confirmButtonText: "{{__('Yes, Generate!')}}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: "{{route(route_prefix().'admin.languages.regenerate.source.texts')}}",
                                data: {
                                    _token : "{{csrf_token()}}",
                                    slug : "{{$lang_slug}}",
                                    type : "{{$type}}",
                                },
                                success : function (){
                                    toastr.success("{{__('source text generate success')}}")
                                    location.reload();
                                }
                            });
                        }
                    });

                });


                $(document).on('submit', '#langauge_translate_form', function (event){
                    event.preventDefault();

                    let form = $(this);
                    let type = form.find('input[name=type]').val();
                    let string_key = form.find('input[name=string_key]').val();
                    let translate_word = form.find('textarea[name=translate_word]').val();

                    $.ajax({
                        type: 'POST',
                        url: '{{route(route_prefix().'admin.languages.words.update', $lang_slug)}}',
                        data: {
                            _token: '{{csrf_token()}}',
                            type: type,
                            string_key: string_key,
                            translate_word: translate_word,
                            slug: "{{$lang_slug}}"
                        },

                        success: function (data){
                            if(data.type === 'success')
                            {
                                toastr.success(data.msg);
                                let translatedString = $('.single-string-wrap .string-part[data-key="'+string_key+'"]').next();
                                translatedString.text(translate_word);
                                translatedString.attr("data-trans",translate_word);
                            }
                        },
                        error: function (data) {
                            let response = JSON.parse(data.responseText);
                            $.each( response.errors, function( key, value) {
                                toastr.error(value);
                            });
                        }
                    });
                })

            });


        })(jQuery);
    </script>
@endsection
