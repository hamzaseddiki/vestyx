@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('All Website Instructions')}}
@endsection

@section('style')
    <x-media-upload.css/>
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
                        <h4 class="card-title mb-5">{{__('All Website Instructions')}}</h4>

                        <span class="text-dark">
                            {{__('Ite will be applied in tenant admin home page like this:')}}

                            <a href="{{url('assets/landlord/admin/images/instruction-demo.png')}}" target="_blank">{{__('View')}}</a>
                        </span>

                        <br><br>
                        <span class="text-danger">{{__('Set this instructions as English (USA) language, because by default tenant language is English (USA)')}}</span>

                        <x-bulk-action permissions="testimonial-delete"/>
                    </x-slot>
                    <x-slot name="right" class="d-flex">
                        <form action="" method="get">
                            <x-fields.select name="lang" title="{{__('Language')}}">
                                @foreach(\App\Models\Language::all()  as $lang)
                                    <option value="{{$lang->slug}}" @if($lang->slug === $lang_slug) selected @endif>{{$lang->name}}</option>
                                @endforeach
                            </x-fields.select>
                        </form>
                        <p></p>
                        <a class="btn btn-info btn-sm mb-3" href="{{route('landlord.admin.tenant.website.instruction.create')}}">{{__('Add New Instruction')}}</a>
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
                        <th>{{__('Image')}}</th>
                        <th>{{__('Title')}}</th>
                        <th>{{__('Description')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </x-slot>
                    <x-slot name="tr">
                        @foreach($all_instructions as $data)
                            <tr>
                                <td>
                                    <x-bulk-delete-checkbox :id="$data->id"/>
                                </td>
                                <td>{{$data->id}}</td>
                                <td>
                                    {!! render_attachment_preview_for_admin($data->image ?? '') !!}
                                </td>

                                <td>
                                    {{ $data->getTranslation('title',$lang_slug)}}
                                </td>
                                <td>{!! \Illuminate\Support\Str::words($data->getTranslation('description',$lang_slug),20)!!}</td>
                                <td>{{ \App\Enums\StatusEnums::getText($data->status)  }}</td>
                                <td>

                                    <a href="{{ route('landlord.admin.tenant.website.instruction.edit',$data->id) }}"
                                       class="btn btn-primary btn-xs mb-3 mr-1 testimonial_edit_btn"
                                       data-bs-placement="top">
                                        <i class="las la-edit"></i>
                                    </a>

                                    <x-delete-popover permissions="testimonial-delete" url="{{route(route_prefix().'admin.tenant.website.instruction.delete', $data->id)}}"/>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-datatable.table>
            </div>
        </div>
    </div>

 <x-media-upload.markup/>

@endsection

@section('scripts')
    <x-media-upload.js/>
    <x-datatable.js/>


    <script>
        $(document).on('change','select[name="lang"]',function (e){
            $(this).closest('form').trigger('submit');
            $('input[name="lang"]').val($(this).val());
        });

        <x-bulk-action-js :url="route( route_prefix().'admin.tenant.website.instruction.bulk.action')" />
    </script>
@endsection
