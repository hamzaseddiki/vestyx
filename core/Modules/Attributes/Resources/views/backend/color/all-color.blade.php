@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('All Product Color')}}
@endsection
@section('style')
    <x-datatable.css />
    <x-bulk-action.css />
@endsection
@section('content')
    @php
        $lang_slug = request()->get('lang') ?? \App\Facades\GlobalLanguage::default_slug();
    @endphp
    <div class="col-lg-12 col-ml-12">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="margin-top-40">
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>
            </div>
            <div class="col-xl-7 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header-wrap d-flex flex-wrap justify-content-between">
                            <h4 class="header-title mb-4">{{__('All Product Colors')}}</h4>

                        @can('product-color-delete')
                            <x-bulk-action.dropdown />
                        @endcan

                        <div class="right">
                            <form action="{{route('tenant.admin.product.colors.all')}}" method="get">
                                <x-fields.select name="lang" title="{{__('Language')}}">
                                    @foreach(\App\Facades\GlobalLanguage::all_languages() as $lang)
                                        <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                    @endforeach
                                </x-fields.select>
                            </form>
                        </div>
                        </div>

                        <div class="table-wrap table-responsive">
                            <table class="table table-default">
                                <thead>
                                <x-bulk-action.th />
                                <th>{{__('ID')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Color Code')}}</th>
                                <th>{{__('Slug')}}</th>
                                <th>{{__('Action')}}</th>
                                </thead>
                                <tbody>
                                    @foreach($product_colors as $product_color)
                                        <tr>
                                            <x-bulk-action.td :id="$product_color->id" />
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{$product_color->getTranslation('name',$default_lang)}}</td>
                                            <td>
                                                <p class="mb-0">{{ $product_color->color_code }}</p>
                                                <p style="background-color: {{$product_color->color_code}}; width: 30px;height: 20px"></p>
                                            </td>
                                            <td>{{ $product_color->slug }}</td>
                                            <td>
                                                @can('product-color-delete')
                                                    <x-table.btn.swal.delete :route="route('tenant.admin.product.colors.delete', $product_color->id)" />
                                                @endcan
                                                @can('product-color-edit')
                                                    <a href="javascript:void(0)"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#color_edit_modal"
                                                       class="btn btn-primary btn-xs mb-3 mr-1 color_edit_btn"
                                                       data-id="{{ $product_color->id }}"
                                                       data-name="{{ $product_color->getTranslation('name',$default_lang) }}"
                                                       data-color_code="{{ $product_color->color_code }}"
                                                       data-slug="{{ $product_color->slug }}"
                                                    >
                                                        <i class="mdi mdi-pencil"></i>
                                                    </a>
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
            @can('product-color-create')
            <div class="col-xl-5 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">{{__('New Color')}}</h4>
                        <form action="{{ route('tenant.admin.product.colors.new') }}" method="POST">
                            @csrf
                            <input type="hidden" name="lang" value="{{$default_lang}}">
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="name" name="name" placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="color_code">{{__('Color Code')}}</label>
                                <input type="color" class="form-control w-25 p-1"  id="color_code" name="color_code" placeholder="{{__('Color Code')}}">
                            </div>
                            <div class="form-group">
                                <label for="slug">{{__('Slug')}}</label>
                                <input type="text" class="form-control"  id="slug" name="slug" placeholder="{{__('Slug')}}">
                            </div>
                            <button class="btn btn-primary px-5 my-3">{{ __('Save Color') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>
    </div>
    @can('product-color-edit')
        <div class="modal fade" id="color_edit_modal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('Edit Product Color')}}</h5>
                        <button type="button" class="close" data-bs-dismiss="modal"><span>×</span></button>
                    </div>
                    <form action="{{route('tenant.admin.product.colors.update')}}"  method="post">
                        <input type="hidden" name="id" id="color_id">
                        <input type="hidden" name="lang" value="{{$default_lang}}">
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="name">{{__('Name')}}</label>
                                <input type="text" class="form-control"  id="edit_name" name="name" placeholder="{{__('Name')}}">
                            </div>
                            <div class="form-group">
                                <label for="color_code">{{__('color_code')}}</label>
                                <input type="color" class="form-control w-25 p-1"  id="edit_color_code" name="color_code" placeholder="{{__('Color Code')}}">
                            </div>
                            <div class="form-group">
                                <label for="slug">{{__('Slug')}}</label>
                                <input type="text" class="form-control"  id="edit_slug" name="slug" placeholder="{{__('Slug')}}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
                            <button type="submit" class="btn btn-primary">{{__('Save Change')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
@section('scripts')
    <x-datatable.js />
    <x-table.btn.swal.js/>
    @can('product-color-delete')
        <x-bulk-action.js :route="route('tenant.admin.product.colors.bulk.action')" />
    @endcan
    <script>
        $(document).ready(function () {

            $(document).on('change','select[name="lang"]',function (e){
                $(this).closest('form').trigger('submit');
                $('input[name="lang"]').val($(this).val());
            });

            $(document).on('click','.color_edit_btn',function(){
                let el = $(this);
                let modal = $('#color_edit_modal');

                modal.find('#color_id').val(el.data('id'));
                modal.find('#edit_name').val(el.data('name'));
                modal.find('#edit_color_code').val(el.data('color_code'));
                modal.find('#edit_slug').val(el.data('slug'));
            });
        });
    </script>
@endsection
