@extends(route_prefix().'admin.admin-master')
@section('title')
    {{__('All Themes Settings')}}
@endsection

@section('style')
    <x-datatable.css/>

    <style>
        .modal-image {
            width: 100%;
        }
    </style>
@endsection

@section('content')

    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <x-admin.header-wrapper>
                        <x-slot name="left">
                            <h4 class="card-title mb-4">{{__('Themes Settings')}}</h4>
                        </x-slot>
                    </x-admin.header-wrapper>
                    <x-error-msg/>
                    <x-flash-msg/>
                </div>

                <div class="row g-4">
                    <form action="{{route('landlord.admin.theme.settings')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{__('Show upcoming themes on home page')}}</label>
                            <label class="switch">
                                <input type="checkbox" name="up_coming_themes_frontend" {{get_static_option('up_coming_themes_frontend') ? 'checked' : ''}}>
                                <span class="slider onff"></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="">{{__('Show upcoming themes on admin panel')}}</label>
                            <label class="switch">
                                <input type="checkbox" name="up_coming_themes_backend" {{get_static_option('up_coming_themes_backend') ? 'checked' : ''}}>
                                <span class="slider onff"></span>
                            </label>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary">{{__('Update')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
