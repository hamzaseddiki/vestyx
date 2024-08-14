@extends(route_prefix().'admin.admin-master')

@section('title') {{__('Price Plan')}} @endsection

@section('style')
    <x-datatable.css/>
@endsection

@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Price Plan')}}</h4>
                        <x-bulk-action permissions="wedding-price-plan-delete"/>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <button class="btn btn-info btn-sm mb-3" data-bs-toggle="modal" data-bs-target="#new_testimonial">{{__('Add New Plan')}}</button>
                    </x-slot>
                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>
                <x-datatable.table>
                    <x-slot name="th">
                        <th class="no-sort">
                            <div class="mark-all-checkbox">
                                <input type="checkbox" class="all-checkbox">
                            </div>
                        </th>
                        <th>{{__('ID')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Price')}}</th>
                        <th>{{__('Is Popular')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_plans as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    {{ $data->getTranslation('title',$lang_slug)}}
                                </td>
                                <td>{{ amount_with_currency_symbol($data->price) }}</td>
                                <td>
                                    @if($data->is_popular == 'on')
                                        <span class="badge badge-success">{{__('Yes')}}</span>
                                    @else
                                        <span class="badge badge-dark">{{__('No')}}</span>
                                    @endif
                                </td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status)  }}</td>
                                <td>
                                    @can('wedding-price-plan-edit')
                                        <a href="#"
                                           data-bs-toggle="modal"
                                           data-bs-target="#testimonial_item_edit_modal"
                                           class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                           data-bs-placement="top"
                                           title="{{__('Edit')}}"
                                           data-id="{{$data->id}}"
                                           data-action="{{route('tenant.admin.wedding.price.plan.update')}}"
                                           data-title="{{$data->getTranslation('title',$default_lang)}}"
                                           data-features="{!! purify_html($data->getTranslation('features',$default_lang)) !!}"
                                           data-not_available_features="{!! purify_html($data->getTranslation('not_available_features',$default_lang)) !!}"
                                           data-price="{{$data->price}}"
                                           data-is_popular="{{$data->is_popular}}"
                                           data-status="{{$data->status}}"
                                        >
                                            <i class="las la-edit"></i>
                                        </a>

                                    @endcan
                                    <x-delete-popover permissions="wedding-price-plan-delete" url="{{route('tenant.admin.wedding.price.plan.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>

    @can('wedding-price-plan-create')
        <div class="modal fade" id="new_testimonial" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('New Plan')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{route('tenant.admin.wedding.price.plan')}}" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <x-fields.input name="title" label="{{__('Title')}}" />

                            <x-fields.textarea name="features" label="{{__('Features')}}" info="{{__('Separate feature by entering (comma) end of the line')}}" />
                            <x-fields.textarea name="not_available_features" label="{{__('Not Available Features')}}" info="{{__('Separate not available feature by entering (comma) end of the line')}}"/>
                            <x-fields.input type="number" name="price" label="{{__('Price')}}" />
                            <x-fields.switcher name="is_popular" label="{{__('Is Popular')}}" />
                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option value="{{\App\Enums\StatusEnums::PUBLISH}}">{{__('Publish')}}</option>
                                <option value="{{\App\Enums\StatusEnums::DRAFT}}">{{__('Draft')}}</option>
                            </x-fields.select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('wedding-price-plan-edit')
        <div class="modal fade" id="testimonial_item_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">{{__('Edit Plan Item')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="#" id="testimonial_edit_modal_form" method="post"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            @csrf
                            <input type="hidden" name="id" value="" class="plan_id">
                            <input type="hidden" name="lang" value="{{$default_lang}}">

                            <x-fields.input name="title" label="{{__('Title')}}" class="edit_title"/>
                            <x-fields.textarea name="features" label="{{__('Features')}}"  class="edit_features" info="{{__('Separate feature by entering (comma) end of the line')}}"/>
                            <x-fields.textarea name="not_available_features" label="{{__('Not Available Features')}}" class="edit_not_available_features" info="{{__('Separate not available feature by entering (comma) end of the line')}}"/>
                            <x-fields.input type="number" name="price" label="{{__('Price')}}"  class="edit_price"/>
                            <x-fields.switcher name="is_popular" label="{{__('Is Popular')}}" class="edit_is_popular"/>
                            <x-fields.select name="status" title="{{__('Status')}}">
                                <option value="1">{{__('Publish')}}</option>
                                <option value="0">{{__('Draft')}}</option>
                            </x-fields.select>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Changes')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            <x-bulk-action-js :url="route('tenant.admin.wedding.price.plan.bulk.action')" />
                $(document).on('change','select[name="lang"]',function (e){
                    $(this).closest('form').trigger('submit');
                    $('input[name="lang"]').val($(this).val());
                });

            $(document).on('click', '.testimonial_edit_btn', function () {
                var el = $(this);
                var id = el.data('id');
                var title = el.data('title');
                var features = el.data('features');
                var not_available_features = el.data('not_available_features');
                var price = el.data('price');
                var is_popular = el.data('is_popular');
                var action = el.data('action');

                var form = $('#testimonial_edit_modal_form');

                if(is_popular == 'on'){
                    form.find('input[name="is_popular"]').prop('checked',true);
                }else{
                    form.find('input[name="is_popular"]').prop('checked',false);
                }

                form.attr('action', action);
                form.find('.plan_id').val(id);
                form.find('.edit_title').val(title);
                form.find('.edit_features').val(features);
                form.find('.edit_not_available_features').val(not_available_features);
                form.find('.edit_price').val(price);

                form.find('.edit_status option[value="' + el.data('status') + '"]').attr('selected', true);

            });

        });
    </script>
@endsection
