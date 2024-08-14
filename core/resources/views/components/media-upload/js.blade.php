<script src="{{global_asset('assets/landlord/admin/js/dropzone.js')}}"></script>
@php
$user_type = $userType ?? 'admin';
$user_id = $user_type == 'user' && auth('web')->check() ? auth('web')->id() : auth('admin')->id();

//routes
$route_name = is_null(tenant()) ? 'landlord' : 'tenant';
// file delete route
$file_delete_route = route($route_name.'.admin.upload.media.file.delete');
// file alt change
$file_alt_change_route = route($route_name.'.admin.upload.media.file.alt.change');
// all file get
$all_file_fetch_route = route($route_name.'.admin.upload.media.file.all');
// load more
$file_load_more_route = route($route_name.'.admin.upload.media.file.loadmore');

@endphp

<script>
    ;(function () {
        "use strict";

        $(document).ready(function (){
            var mainUploadBtn = '';

            //after select image
            $(document).on('click','.media_upload_modal_submit_btn',function (e) {
                e.preventDefault();
                var allData = $('.media-uploader-image-list li.selected');
                if( typeof allData != 'undefined'){
                    mainUploadBtn.parent().find('.img-wrap').html('');
                    var imageId = '';
                    $.each(allData,function(index,value){
                        var el = $(this).data();
                        var separator = allData.length == index ? '' : '|';
                        imageId += el.imgid + separator;
                        mainUploadBtn.prev('input').attr('data-imgsrc',el.imgsrc);
                        mainUploadBtn.parent().find('.img-wrap').append('<div class="attachment-preview"><div class="thumbnail"><div class="centered"><img src="'+el.imgsrc+'"></div></div></div>');
                    });
                    mainUploadBtn.prev('input').val(imageId.substring(0,imageId.length -1));

                }
                closeMediaUploadModal();
                mainUploadBtn.text('Change');
            });


            //delete image form media uploader
            $(document).on('click','.media_library_image_delete_btn',function (e) {
                e.preventDefault();
                Swal.fire({
                    title: '{{__("Are you sure to delete this image")}}',
                    text: '{{__("This image will remove permanently")}}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Delete It'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteImage();
                    }
                });
            });

            function deleteImage(){
                $.ajax({
                    type: "POST",
                    url: "{{$file_delete_route}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        img_id : $('.image_id').text(),
                        user_type: "{{$user_type}}"
                    },
                    success: function (data) {
                        $('.media-uploader-image-info a,.media-uploader-image-info .img-meta').removeClass('d-none');
                        $('.media-uploader-image-list li.selected').remove();
                        $('.media-uploader-image-info .img-wrapper img').attr('src','');
                        $('.media-uploader-image-info .img-info .img-title').text('');
                    },
                    error: function (error) {

                    }
                });
            }


            $(document).on('click','.media-upload-btn-wrapper .img-wrap .rmv-span',function (e) {
                //imlement remove image icon
                var el = $(this);
                el.parent().parent().find('input[type="hidden"]').val('');
                el.parent().parent().find('.attachment-preview').html('');
                el.parent().parent().find('.media_upload_form_btn').attr('data-imgid','');
                el.hide();
            })

            $(document).on('click','.media_upload_form_btn',function (e) {
                e.preventDefault();

                var parent = $('#media_upload_modal');
                var el = $(this);
                var imageId = el.prev('input').val();
                mainUploadBtn = el;
                var loadAllImage = $('#load_all_media_images');

                parent.find('.media_upload_modal_submit_btn').text(el.data('btntitle'));
                parent.find('.modal-title').text(el.data('modaltitle'));

                if(el.data('mulitple')){
                    parent.attr('data-mulitple','true')
                }else{
                    parent.removeAttr('data-mulitple');
                }
                loadAllImage.attr('data-selectedimage','');
                if(imageId =! ''){
                    loadAllImage.attr('data-selectedimage',el.prev('input').val());
                    loadAllImage.trigger('click');
                }
            });

            $('body').on('click', '.media-uploader-image-list > li', function (e) {
                e.preventDefault();
                var el = $(this);
                var allData = el.data();

                if( typeof $('#media_upload_modal').attr('data-mulitple') == 'undefined'){
                    el.toggleClass('selected').siblings().removeClass('selected');
                }else{
                    el.toggleClass('selected');
                }

                $('.media-uploader-image-info a,.media-uploader-image-info .img-meta,.delete_image_form').removeClass('d-none');

                var parent = $('.img-meta');
                parent.children('.date').text(allData.date);
                parent.children('.dimension').text(allData.dimension);
                parent.children('.size').text(allData.size);
                parent.children('.imgsrc').text(allData.imgsrc);
                parent.children('.image_id').text(allData.imgid);
                parent.find('input[name="img_alt_tag"]').val(allData.alt);
                parent.parent().find('input[name="img_id"]').val(allData.imgid);

                $('.img_alt_submit_btn').html('<i class="mdi mdi-check-circle"></i>');
                $('.img-info .img-title').text(allData.title)
                if(allData.imgsrc != ''){
                    $('.media-uploader-image-info .img-wrapper img').attr('src',allData.imgsrc);
                }
            });

            Dropzone.options.placeholderfForm = {
                dictDefaultMessage: "{{__('Drag or Select Your Image')}}",
                maxFiles: 50,
                maxFilesize: 10, //MB
                acceptedFiles: 'image/*,application/pdf,.doc,.docx,.txt,.svg,.zip',
                success: function (file, response) {
                    if (file.previewElement) {
                        return file.previewElement.classList.add("dz-success");
                    }
                    $('#load_all_media_images').trigger('click');
                    $('.media-uploader-image-list li:first-child').addClass('selected');
                },
                error: function (file, message) {
                    if (file.previewElement) {
                        file.previewElement.classList.add("dz-error");
                        if ((typeof message !== "String") && message.error) {
                            message = message.error;
                        }
                        for (let node of file.previewElement.querySelectorAll("[data-dz-errormessage]")) {
                            node.textContent = message.errors.file[0];
                        }
                    }
                }
            };


            $(document).on('click', '#upload_media_image', function (e) {
                e.preventDefault();
                $('.media_upload_modal_submit_btn').addClass('d-none');
            });


            $(document).on('click', '#load_all_media_images', function (e) {
                e.preventDefault();
                loadAllImages();
            });
            $(document).on('click', '.img_alt_submit_btn', function (e) {
                e.preventDefault();
                var parent = $(this).parent().parent().parent();
                var alt = $(this).prev('input').val();
                var imgId = parent.find('.image_id').text();

                $.ajax({
                    type: "POST",
                    url: "{{$file_alt_change_route}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        imgid: parseInt(imgId),
                        alt: alt,
                        user_type: "{{$user_type}}"
                    },
                    success: function (data) {
                        $('.img_alt_submit_btn').html('<i class="mdi mdi-check-circle"></i>');
                        $('.media-uploader-image-list li[data-imgid="'+imgId+'"]').data('alt',alt);
                    }
                });
            });

            function loadAllImages() {
                var selectedImage = $('#load_all_media_images').attr('data-selectedimage');
                $.ajax({
                    type: "POST",
                    url: "{{$all_file_fetch_route}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        'selected' : selectedImage,
                        user_type: "{{$user_type}}"
                    },
                    success: function (data) {
                        $('.media-uploader-image-list').html('');
                        $.each(data,function (index,value) {
                            var imageMarkup ='<li data-date="'+value.upload_at+'" data-imgid="'+value.image_id+'" data-imgsrc="'+value.img_url+'" data-size="'+value.size+'" data-dimension="'+value.dimensions+'" data-title="'+value.title+'" data-alt="'+value.alt+'">\n' +
                                '<div class="attachment-preview">\n' +
                                '<div class="thumbnail">\n' +
                                '<div class="centered">\n' ;
                            if (['pdf','doc','docx','txt','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'].includes(value.type)){
                                imageMarkup += '<i class="fas fa-file file-icon"></i> \n' ;
                                imageMarkup += '<span class="file-name">'+value.type+'</span> \n' ;
                            }else{
                                imageMarkup += '<img src="'+value.img_url+'" alt="">\n' ;
                            }

                            imageMarkup += '</div>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</li>';
                            if($('.media-uploader-image-list[data-imgid="'+value.image_id+'"').length <1){
                                $('.media-uploader-image-list').append(imageMarkup);
                            }

                        });
                        hidePreloader();
                        $('.media_upload_modal_submit_btn').removeClass('d-none');
                        selectOldImage();
                        $('#loadmorewrap button').show();
                    },
                    error: function (error) {

                    }
                });
            }
            $(document).on('click','.media_upload_form_btn',function(e){
               e.preventDefault();
               $('#media_upload_modal').addClass('show');
               if($('#media_upload_modal_backdrop').length < 1){
                   $('body').append('<div id="media_upload_modal_backdrop" class="media_upload_modal_backdrop"></div>');
               }
            });
            /* remove media upload backdrop */
            $(document).on('click','#media_upload_modal_backdrop,#media_upload_popup_close_btn',function(e){
                e.preventDefault();
                closeMediaUploadModal();
                console.log('dd')
            });

            function closeMediaUploadModal(){
                let mediaBackdrop =  $('#media_upload_modal_backdrop');
                $('#media_upload_modal').removeClass('show');
                if(mediaBackdrop.length > 0){
                    mediaBackdrop.remove();
                }
            }


            /**
             * hide preloader
             * @since 2.2
             * */
            function hidePreloader() {
                $('.image-preloader-wrapper').hide(300);
            }

            /**
             * Select preveiously selected image
             * @since 2.2
             * */
            function selectOldImage(){
                var imageId = mainUploadBtn.prev('input').val();
                var matches = imageId.match(/([|])/g);
                if(matches != null){
                    var imgArr = imageId.split('|');
                    var filtered = imgArr.filter(function (el) {
                        return el != "";
                    });
                    $.each(filtered,function(index,value){
                        $('.media-uploader-image-list li[data-imgid="'+value+'"]').trigger('click');
                    });
                }else{
                    $('.media-uploader-image-list li[data-imgid="'+imageId+'"]').trigger('click').siblings().removeClass('selected');
                }

            }

            /* loadmore image  */
            $(document).on('click','#loadmorewrap',function (){
                var mediaImageWrapper = $('#media_library');
                var skipp = mediaImageWrapper.find('ul.media-uploader-image-list li').length - 1;
                $('#loadmorewrap button').text('{{__('Loading...')}}');
                $.ajax({
                    type: "POST",
                    url: "{{$file_load_more_route}}",
                    data: {
                        _token: "{{csrf_token()}}",
                        'skip' : skipp,
                        user_type: "{{$user_type}}"
                    },
                    success: function (data) {
                        $.each(data,function (index,value) {

                            var imageMarkup ='<li data-date="'+value.upload_at+'" data-imgid="'+value.image_id+'" data-imgsrc="'+value.img_url+'" data-size="'+value.size+'" data-dimension="'+value.dimensions+'" data-title="'+value.title+'" data-alt="'+value.alt+'">\n' +
                                '<div class="attachment-preview">\n' +
                                '<div class="thumbnail">\n' +
                                '<div class="centered">\n' ;
                            if (['pdf','doc','docx','txt','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'].includes(value.type)){
                                imageMarkup += '<i class="fas fa-file file-icon"></i> \n' ;
                                imageMarkup += '<span class="file-name">'+value.type+'</span> \n' ;
                            }else{
                                imageMarkup += '<img src="'+value.img_url+'" alt="">\n' ;
                            }

                            imageMarkup += '</div>\n' +
                                '</div>\n' +
                                '</div>\n' +
                                '</li>';
                            if($('.media-uploader-image-list[data-imgid="'+value.image_id+'"').length <1){
                                mediaImageWrapper.find('.media-uploader-image-list').append(imageMarkup);
                            }
                        });
                        if(data == ''){
                            $('#loadmorewrap button').hide();
                        }
                        $('#loadmorewrap button').text('{{__('Loadmore')}}');
                    },
                    error: function (error) {

                    }
                });
            });
        });
    })();
</script>
