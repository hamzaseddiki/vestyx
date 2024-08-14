@extends(route_prefix().'admin.admin-master')
@section('title') {{__('All Price Plan')}} @endsection
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
                <x-error-msg/>
                <x-flash-msg/>
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__('All Price Plan')}}</h4>
                    </x-slot>
                    <x-slot name="right" class="d-flex">

                        <form action="{{route(route_prefix().'admin.price.plan')}}" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Facades\GlobalLanguage::all_languages(1) as $lang)
                                    @php
                                        $slug = $lang->slug;
                                    @endphp
                                    <option value="{{$slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <x-link-with-popover permissions="price-plan-create" url="{{route(route_prefix().'admin.price.plan.create')}}" extraclass="ml-3">
                            {{__('Create Price Plan')}}
                        </x-link-with-popover>
                    </x-slot>
                </x-admin.header-wrapper>

                <x-datatable.table>
                    <x-slot name="th">
                        <th>{{__('Id')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Price')}}</th>
                        <th>{{__('Type')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Created')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_plans as $plan)
                            <tr>
                                <td>{{$plan->id}}</td>
                                <td>
                                    {{ $plan->getTranslation('title',$lang_slug)}}

                                    @if($plan->has_trial == 1)
                                        <span class="badge badge-info">
                                      {{__('On Trial')}}
                                    </span>
                                    @endif
                                </td>
                                <td>{{ amount_with_currency_symbol($plan->price) }}</td>
                                <td>
                                    @if($plan->type == 0)
                                    <span class="badge badge-success">{{ \App\Enums\PricePlanTypEnums::getText($plan->type)  }}</span>
                                     @elseif($plan->type == 1)
                                        <span class="badge badge-dark">{{ \App\Enums\PricePlanTypEnums::getText($plan->type)  }}</span>
                                     @elseif($plan->type == 2)
                                        <span class="badge badge-info">{{ \App\Enums\PricePlanTypEnums::getText($plan->type)  }}</span>
                                    @elseif($plan->type == 3)
                                        <span class="badge badge-primary">{{ \App\Enums\PricePlanTypEnums::getText($plan->type)  }}</span>
                                    @endif
                                </td>
                                <td>{{ \App\Enums\StatusEnums::getText($plan->status)  }}</td>
                                <td>{{$plan->created_at->format('D, d-m-y')}}</td>
                                <td>
                                    <x-delete-popover permissions="price-plan-delete" url="{{route(route_prefix().'admin.price.plan.delete', $plan->id)}}"/>
                                    <x-link-with-popover permissions="price-plan-edit" url="{{route(route_prefix().'admin.price.plan.edit', $plan->id)}}">
                                        <i class="mdi mdi-pencil"></i>
                                    </x-link-with-popover>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>

            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <x-datatable.js/>
    <script>
        $(document).ready(function($){
            "use strict";

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

        });
    </script>
@endsection
