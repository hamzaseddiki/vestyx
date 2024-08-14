@extends(route_prefix().'admin.admin-master')

@section('style')
    <x-datatable.css/>
    <x-media-upload.css/>
    <style>
        .all_donation_info_column li{
            list-style-type: none;
        }
    </style>
@endsection

@section('title')
    {{__('All Donations Activities')}}
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <x-error-msg/>
                        <x-flash-msg/>
                        <x-admin.header-wrapper>
                            <x-slot name="left">
                                <h4 class="card-title mb-5">{{__('All Donation Activities')}}</h4>

                                <x-bulk-action permissions="donation-activity-delete"/>
                            </x-slot>
                            <x-slot name="right" class="d-flex">
                                <form action="{{route('tenant.admin.donation.activity')}}" method="get">
                                    <x-fields.select name="lang" title="{{__('Language')}}">
                                        @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                            <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                        @endforeach
                                    </x-fields.select>
                                </form>
                                <p></p>
                                @can('donation-activity-create')
                                <a href="{{route('tenant.admin.donation.activity.new')}}"
                                   class="btn btn-info btn-sm mb-3">{{__('Add New')}}</a>
                                @endcan
                            </x-slot>
                        </x-admin.header-wrapper>

                        <x-datatable.table>
                            <x-slot name="th">
                                <th class="no-sort">
                                    <div class="mark-all-checkbox">
                                        <input type="checkbox" class="all-checkbox">
                                    </div>
                                </th>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Title')}}</th>
                                <th>{{__('Category')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                            </x-slot>
                            <x-slot name="tr">
                                @foreach($all_activities as $data)
                                    <tr>
                                        <td>
                                            <x-bulk-delete-checkbox :id="$data->id"/>
                                        </td>
                                        <td>{{$data->id}}</td>
                                        <td>
                                            {{ $data->getTranslation('title',$lang_slug)}}
                                        </td>
                                        <td>
                                            {{ optional($data->category)->getTranslation('title',$lang_slug)}}
                                        </td>
                                        <td>
                                            @php
                                                $testimonial_img = get_attachment_image_by_id($data->image,null,true);
                                            @endphp
                                            {!! render_attachment_preview_for_admin($data->image ?? '') !!}
                                            @php  $img_url = $testimonial_img['img_url']; @endphp
                                        </td>
                                        <td>
                                            {{ \App\Enums\StatusEnums::getText($data->status) }}
                                        </td>
                                        <td>
                                            @can('donation-activity-edit')
                                                <a href="{{route('tenant.admin.donation.activity.edit',$data->id)}}" class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn">
                                                    <i class="las la-edit"></i>
                                                </a>
                                            @endcan

                                            @can('donation-activity-delete')
                                            <x-delete-popover url="{{route('tenant.admin.donation.activity.delete', $data->id)}}"/>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-datatable.table>
                    </div>
                </div>
            </div>
        </div>
    </div>


   <x-media-upload.markup/>
@endsection

@section('scripts')
    <x-datatable.js/>
    <script src="{{asset('assets/backend/js/dropzone.js')}}"></script>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function () {
                <x-bulk-action-js :url="route('tenant.admin.donation.activity.bulk.action')"/>
                    $(document).on('change','select[name="lang"]',function (e){
                        $(this).closest('form').trigger('submit');
                        $('input[name="lang"]').val($(this).val());
                    });
            })
        })(jQuery)
    </script>
    <x-media-upload.js/>

@endsection
