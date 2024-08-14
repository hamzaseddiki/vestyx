<link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/dropzone.css')}}">
<link rel="stylesheet" href="{{global_asset('assets/landlord/admin/css/media-uploader.css')}}">
<style>
    .media_upload_modal_backdrop{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 2900;
        width: 100vw;
        height: 100vh;
        background-color: #000000;
        opacity: .5;
    }
    .media-upload-modal-container{
        position: fixed;
        top: 0;
        left: 0;
        z-index: 3050;
        width: 100%;
        height: 100%;
        overflow-x: hidden;
        overflow-y: auto;
        outline: 0;
        display: none;
        transition: opacity 0.15s linear;
    }
    .media-upload-modal-container.show{
        display: block;
        opacity: 1;
    }
    #media_upload_popup_close_btn {
        background-color: #df0909;
        border: none;
        color: #fff;
        opacity: 1;
        display: inline-block;
        width: 30px;
        height: 30px;
        text-align: center;
        padding: 0;
        line-height: 30px;
    }
</style>
