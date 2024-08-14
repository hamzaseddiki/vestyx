@extends('backend.admin-master')
@section('site-title')
    {{__('All Campaign')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12 padding-bottom-30">
        <div class="row">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-msg.error />
                    <x-msg.flash />
                </div>
            </div>
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('All Campaign')}}</h4>
                        @can('campaign-create')
                            <div class="text-right">
                                <a href="{{ route('admin.campaigns.new') }}" class="btn btn-primary">{{ __('Add New Campaign') }}</a>
                            </div>
                        @endcan
                        @can('campaign-delete')
                            <x-bulk-action.dropdown />
                        @endcan
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Image')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_campaigns as $campaign)
                                    <tr>
                                        <x-bulk-action.td :id="$campaign->id" />
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $campaign->title }}</td>
                                        <x-table.td-image :image="$campaign->image" />
                                        <td><x-status-span :status="$campaign->status"/></td>
                                        <td>
                                            @if($campaign->id != 1)
                                                @can('campaign-delete')
                                                    <x-delete-popover :url="route('admin.campaigns.delete', $campaign->id)"/>
                                                @endcan
                                            @endif
                                            @can('campaign-edit')
                                                <x-table.btn.edit :route="route('admin.campaigns.edit', $campaign->id)" />
                                            @endcan
                                            <a target="_blank" class="btn btn-info btn-xs mb-3 mr-1"
                                               href="{{ route('frontend.products.campaign', ['id' => $campaign->id, 'slug' => \Str::slug($campaign->title)]) }}">
                                                <i class="ti-eye"></i>
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
    </div>
@endsection
@section('script')
    <x-datatable.js />
    <x-table.btn.swal.js />
    <x-bulk-action.js :route="route('admin.campaigns.bulk.action')" />

    <script>
        $(document).ready(function () {
            $(document).on('click','.campaign_edit_btn',function(){
                let el = $(this);
                let id = el.data('id');
                let name = el.data('name');
                let modal = $('#campaign_edit_modal');

                modal.find('#campaign_id').val(id);
                modal.find('#edit_name').val(name);

                modal.show();
            });
        });
    </script>

@endsection
