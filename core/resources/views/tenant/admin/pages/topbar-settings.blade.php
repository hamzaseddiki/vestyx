@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Topbar Settings')}}
@endsection

@section('style')
    <link href="{{ global_asset('assets/common/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40"></div>
                <x-error-msg/>
                <x-flash-msg/>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrapp d-flex justify-content-between">
                            <h4 class="header-title">{{__('Social Icons')}}  </h4>
                            <div class="header-title">
                                <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4 " data-bs-toggle="modal" data-bs-target="#add_social_icon">{{__('Add New')}}</button>
                            </div>
                        </div>
                        <table class="table table-default">
                            <thead>
                            <th>{{__('ID')}}</th>
                            <th>{{__('Icon')}}</th>
                            <th>{{__('URL')}}</th>
                            <th>{{__('Action')}}</th>
                            </thead>
                            <tbody>
                            @foreach($all_social_icons as $data)
                                <tr>
                                    <td>{{$data->id}}</td>
                                    <td class="view"><i class="{{$data->icon}}"></i></td>
                                    <td>{{$data->url}}</td>
                                    <td>
                                        <x-delete-popover url="{{route(route_prefix().'admin.delete.social.item', $data->id)}}"/>
                                        <a href="#"
                                           data-bs-toggle="modal"
                                           data-bs-target="#social_item_edit_modal"
                                           class="btn btn-lg btn-primary btn-sm mb-3 mr-1 social_item_edit_btn"
                                           data-id="{{$data->id}}"
                                           data-url="{{$data->url}}"
                                           data-icon="{{$data->icon}}"
                                        >
                                            <i class="las la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="add_social_icon" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Add Social Item')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route(route_prefix().'admin.new.social.item')}}"  method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="icon" class="d-block">{{__('Icon')}}</label>
                            <div class="btn-group ">
                                <button type="button" class="btn btn-primary iconpicker-component">
                                    <i class="las la-edit"></i>
                                </button>
                                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="las la-edit" data-bs-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">{{__('Toggle Dropdown')}}</span>
                                </button>
                                <div class="dropdown-menu"></div>
                            </div>
                            <input type="hidden" class="form-control"  id="icon" value="las la-user" name="icon">
                        </div>
                        <div class="form-group">
                            <label for="social_item_link">{{__('URL')}}</label>
                            <input type="text" name="url" id="social_item_link"  class="form-control" >
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Add Social Item')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="social_item_edit_modal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('Edit Social Item')}}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                </div>
                <form action="{{route(route_prefix().'admin.update.social.item')}}"  method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="id" id="social_item_id" value="">
                        <div class="form-group">
                            <label for="icon" class="d-block">{{__('Icon')}}</label>
                            <div class="btn-group edit_icon">
                                <button type="button" class="btn btn-primary iconpicker-component">
                                    <i class="las la-edit"></i>
                                </button>
                                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                        data-selected="las la-edit" data-bs-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">{{__('Toggle Dropdown')}}</span>
                                </button>
                                <div class="dropdown-menu"></div>
                            </div>
                            <input type="hidden" class="form-control"  id="edit_social_icon" value="las la-user" name="icon">
                        </div>

                        <div class="form-group">
                            <label for="social_item_edit_url">{{__('Url')}}</label>
                            <input type="text" class="form-control"  id="social_item_edit_url" name="url" placeholder="{{__('Url')}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                        <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{global_asset('assets/common/js/fontawesome-iconpicker.min.js')}}"></script>
    <script>
        (function($){
            "use strict";

            $(document).ready(function () {
                <x-icon-picker/>
                $(document).on('click','.social_item_edit_btn',function(){
                    var el = $(this);
                    var id = el.data('id');
                    var url = el.data('url');
                    var icon = el.data('icon');

                    var form = $('#social_item_edit_modal');
                    form.find('#social_item_id').val(id);
                    form.find('#edit_social_icon').val(icon);
                    form.find('#social_item_edit_url').val(url);
                    form.find('#edit_icon').val(el.data('icon'));
                    form.find('.edit_icon .icp-dd').attr('data-selected', el.data('icon'));
                    form.find('.edit_icon .iconpicker-component i').attr('class', el.data('icon'));
                });

            })

        })(jQuery);
    </script>
@endsection
