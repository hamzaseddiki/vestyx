@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Sitemap Settings')}}
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-12 mt-5">
                <x-error-msg/>
                <x-flash-msg/>
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__("Sitemap Settings")}}</h4>

                        <form action="{{route(route_prefix().'admin.general.sitemap.settings')}}" id="sitemap_form" method="post" enctype="multipart/form-data">
                            @csrf

                            @php
                                $url = url('/');
                            @endphp
                            <div class="form-group">
                                <input type="hidden" class="form-control site_url_data" name="site_url" value="{{$url}}">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary my-3 sitemap_button " id="custom">{{__('Generate Now')}}</button>
                                <br>
                                <small class="text-danger">{{__('It will take time to generate sitemap..Please increase your server executing time over ( 300 seconds )')}}</small>
                            </div>
                        </form>
                        <table class="table table-default">
                            <thead>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Size')}}</th>
                            <th>{{__('Action')}}</th>
                            </thead>
                            <tbody>
                            @foreach($all_sitemap as $data)

                                <tr>
                                    <td>{{basename($data)}}</td>
                                    <td>{{date('j F Y - h:m:s',filectime($data)) }}</td>
                                    <td>
                                        @if(trim(formatBytes(filesize($data))) === 'NAN')
                                            {{__('0 Byte')}}
                                        @else
                                            {{formatBytes(filesize($data))}}
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-xs text-white btn-danger mb-3 mr-1 delete_sitemap_xml_file_btn">
                                            <i class="las la-trash"></i>
                                        </a>
                                        <form method='post' class="d-none delete_sitemap_file_form"  action='{{route(route_prefix()."admin.general.sitemap.settings.delete")}}'>
                                            @csrf
                                            <input type='hidden' name='sitemap_name' value='{{$data}}'>
                                            <input type='submit' class='btn btn-danger btn-xs' value='{{__('Yes, Please')}}'>
                                        </form>
                                        @if(!tenant())
                                        <a href="{{url('sitemap/landlord')}}/{{basename($data)}}" download class="btn btn-primary btn-xs mb-3 mr-1"> <i class="las la-download"></i> </a>
                                         @else
                                            <a href="{{url('sitemap/tenants')}}/{{basename($data)}}" download class="btn btn-primary btn-xs mb-3 mr-1"> <i class="las la-download"></i> </a>
                                        @endif
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

@endsection

@section('scripts')

    <script>
        <x-btn.custom :title="'Generating..'"/>
            (function($){
                "use strict";

        $(document).on('click','.delete_sitemap_xml_file_btn',function(e){
            e.preventDefault();
            Swal.fire({
                title: '{{__("Are you sure to delete it?")}}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete It!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(this).next('.delete_sitemap_file_form').find('input[type="submit"]').trigger('click');
                }
            });
        });

            })(jQuery);
    </script>
@endsection
