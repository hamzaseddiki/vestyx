@extends('tenant.admin.admin-master')
@section('title')
    {{__('Product Inventory')}}
@endsection
@section('style')
    <x-datatable.css/>
    <x-bulk-action.css/>
@endsection
@section('content')
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-4">{{__('Product Inventory')}}</h4>
                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('SKU')}}</th>
                                <th>{{__('Stock')}}</th>
                                <th>{{__('Sold')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                @foreach($all_inventory_products as $inventory)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $inventory?->product?->getTranslation('name',default_lang()) }}</td>
                                        <td>{{ $inventory->sku }}</td>
                                        <td>{{ $inventory->stock_count ?? 0 }}</td>
                                        <td>{{ $inventory->sold_count ?? 0 }}</td>
                                        <td>
                                            @can('product-inventory-edit')
                                                <x-table.btn.edit
                                                        :route="route('tenant.admin.product.inventory.edit', $inventory->id)"/>
                                            @endcan
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
