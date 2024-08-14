@extends(route_prefix().'admin.admin-master')
@section('title') {{__('Admin Health')}} @endsection

@section('style')
    <x-media-upload.css/>
    <x-datatable.css/>
@endsection

@section('content')
    @php
      $display_errors =  "ini_get method not allowed";
      $memory_limit =  "ini_get method not allowed";
      $post_max_size =  "ini_get method not allowed";
      $max_execution_time =  "ini_get method not allowed";
      $upload_max_filesize =  "ini_get method not allowed";

   phpinfo();
          if (function_exists('ini_get')){
              $display_errors =  ini_get("display_errors");

              $memory_limit =  ini_get("memory_limit");
              $post_max_size =  ini_get("post_max_size");
              $max_execution_time =  ini_get("max_execution_time");
              $upload_max_filesize =  ini_get("upload_max_filesize");
          }
    @endphp

    <div class="row">
        <div class="col-sm-6 m-auto">
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  PHP version
                    <span class="badge badge-info badge-pill">
                        @php
                         echo "V"." ".phpversion();
                        @endphp
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    MySQL version
                    <span class="badge badge-info badge-pill">
                        @php
                            echo "V"." ". DB::select("SELECT VERSION() as version")[0]->version;
                        @endphp
                    </span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                 Laravel version
                    <span class="badge badge-info badge-pill">
                        @php
                            echo "V"." ".app()->version();
                        @endphp
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Database create permission
                    @php
                        $website_has_permission_to_create_database = get_static_option('website_has_permission_to_create_database');
                    @endphp
                    <span class="badge @if($website_has_permission_to_create_database === 'yes') badge-success @else badge-danger @endif  badge-pill">{{$website_has_permission_to_create_database}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Wildcard subdomain
                    @php
                        $website_has_permission_to_create_database = get_static_option('website_has_permission_to_create_database');
                    @endphp
                    <span class="badge @if($website_has_permission_to_create_database == 'yes') badge-success @else badge-danger @endif badge-pill">{{$website_has_permission_to_create_database}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Wildcard SSL
                    @php
                        $website_wildcard_subdomain_working = get_static_option('website_wildcard_subdomain_working');
                    @endphp
                    <span class="badge @if($website_wildcard_subdomain_working == 'yes') badge-success @else badge-danger @endif badge-pill">{{$website_wildcard_subdomain_working}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Cron Job
                    @php
                        $website_cron_job = get_static_option('website_cron_job');
                    @endphp
                    <span class="badge @if($website_cron_job == 'yes') badge-success @else badge-danger @endif badge-pill">{{$website_cron_job}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                   <p> Memory Limit <small class="d-block">{{__('recommended memory limit is 512MB')}}</small></p>
                    <span class="badge badge-success badge-pill">{{$memory_limit}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p> Maximum Execution Time <small class="d-block">{{__('recommended maximum execution time is 300')}}</small></p>

                    <span class="badge badge-success badge-pill">{{$max_execution_time}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Display Errors
                    <span class="badge @if($display_errors == 'Off') badge-danger @else badge-success @endif badge-pill">{{$display_errors}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p> Max File Upload Size <small class="d-block">{{__('recommended post size is 128M')}}</small></p>
                    <span class="badge badge-success badge-pill">{{$upload_max_filesize}}</span>
                </li>

                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <p> Post Max Size <small class="d-block">{{__('recommended post size is 128M')}}</small></p>
                    <span class="badge badge-success badge-pill">{{$post_max_size}}</span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    Database engine
                    <span class="badge badge-info badge-pill">{{\Config::get('database.connections.mysql.engine')}}</span>
                </li>

                <li class="list-group-item d-flex  justify-content-start align-items-center flex-wrap">
                    <p class="d-block mb-3">{{__('Php Extension list')}}</p>
                    @php
                        $colors = ["badge-success",'badge-primary','badge-secondary','badge-danger','badge-warning'];
                    @endphp
                    @foreach(get_loaded_extensions() ?? [] as $ext)
                    <span class="badge badge-secondary badge-pill m-1 extension">{{$ext}}</span>
                    @endforeach
                </li>


            </ul>
        </div>
    </div>
{{--end --}}



@endsection
@section('scripts')
    <!-- Start datatable js -->
    <x-datatable.js/>
    <x-media-upload.js/>
    <script>
        (function($){
            "use strict";
            $(document).ready(function() {
                $(document).on('click','.user_change_password_btn',function(e){
                    e.preventDefault();
                    var el = $(this);
                    var form = $('#user_password_change_modal_form');
                    form.find('#ch_user_id').val(el.data('id'));
                });
                $('#all_user_table').DataTable( {
                    "order": [[ 0, "desc" ]]
                } );

            } );

        })(jQuery);
    </script>
@endsection
