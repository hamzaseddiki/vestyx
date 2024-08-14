@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('Blog Comments')}}
@endsection
@section('style')
   <x-datatable.css/>
@endsection
@section('content')
  <div class="col-lg-12 col-ml-12 padding-bottom-30">
       <div class="row">
           <div class="col-lg-12">
               <div class="margin-top-40"></div>
               <x-error-msg/>
               <x-flash-msg/>
           </div>
           <div class="col-lg-12 mt-5">
               <div class="card">
                   <div class="card-body">
                       <div class="header-wrap d-flex justify-content-between">
                           <div class="left-content">
                               <h4 class="header-title">{{__('All Comments of : ') }} <span class="text-primary ml-1">{{$blog_comments->getTranslation('title',get_user_lang())}}</span>  </h4>
                               <x-bulk-action/>
                           </div>
                           <div class="header-title d-flex">

                               <div class="btn-wrapper-inner ml-2">
                                   <a class="btn btn-info btn-sm" href="{{route(route_prefix().'admin.blog')}}">{{__('Go Back')}}</a>
                               </div>
                           </div>
                       </div>
                       <div class="table-wrap table-responsive">
                           <table class="table table-default table-striped table-bordered">
                               <thead class="text-white">
                                   <th class="no-sort">
                                      <div class="mark-all-checkbox">
                                          <input type="checkbox" class="all-checkbox">
                                      </div>
                                  </th>
                                   <th>{{__('ID')}}</th>
                                   <th>{{__('Commented By')}}</th>
                                   <th>{{__('Comments')}}</th>
                                   <th>{{__('Action')}}</th>
                                   </thead>
                                   <tbody>
                                   @foreach($blog_comments->comments ?? [] as $data)
                                       <tr>
                                         <td>
                                             <div class="bulk-checkbox-wrapper">
                                                 <input type="checkbox" class="bulk-checkbox" name="bulk_delete[]" value="{{$data->id}}">
                                             </div>
                                         </td>
                                           <td>{{$data->id}}</td>
                                           <td>{{$data->commented_by}}</td>
                                           <td>{{$data->comment_content}}</td>

                                           <td>
                                               <x-delete-popover url="{{route(route_prefix().'admin.blog.comments.delete.all.lang', $data->id)}}"/>
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
</div>

@endsection

@section('scripts')
    <script>
        (function ($){
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route(route_prefix().'admin.blog.comments.bulk.action')" />

            });
        })(jQuery)
    </script>
    <x-datatable.js/>
@endsection
