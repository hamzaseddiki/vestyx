<div class="modal fade" data-selected="" id="{{$target}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{$title}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="modal-success-msg py-2 mb-4">
                        <h3 class="themeName text-center mb-0"></h3>
                    </div>

                    <div class="col-6">
                        <img class="modal-image" src="" alt="">
                    </div>

                    <div class="col-6">
                        <h2></h2>
                        <p></p>

                        <a href="javascript:void(0)" class="text-capitalize theme_status_update_button btn btn-success btn-sm"></a>

                        @if($user != 'tenant')
                            <a href="javascript:void(0)" class="edit-btn text-capitalize btn btn-info btn-sm"
                               data-bs-toggle="modal"
                               data-bs-target="#edit-modal"
                               data-id=""
                               data-name=""
                               data-description="">{{__('Edit Details')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
