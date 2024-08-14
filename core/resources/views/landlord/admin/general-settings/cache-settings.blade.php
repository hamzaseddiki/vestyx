@extends(route_prefix().'admin.admin-master')

@section('title')
    {{__('Cache Settings')}}
@endsection

@section('style')
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">{{__('Cache Identity')}}</h4>
                <x-error-msg/>
                <x-flash-msg/>
                    <form action="{{route(route_prefix().'admin.general.cache.settings')}}" method="post">
                        @csrf
                        <input type="hidden" name="type" value="route">
                        <x-button class="success" type="submit">
                            {{__('Clear Route:Cache')}}
                        </x-button>
                    </form>
                <form action="{{route(route_prefix().'admin.general.cache.settings')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="view">
                    <x-button class="success" type="submit" >
                        {{__('Clear View:Cache')}}
                    </x-button>
                </form>
                <form action="{{route(route_prefix().'admin.general.cache.settings')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="config">
                    <x-button class="success" type="submit" >
                        {{__('Clear Config:Cache')}}
                    </x-button>
                </form>
                <form action="{{route(route_prefix().'admin.general.cache.settings')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="event">
                    <x-button class="success" type="submit" >
                        {{__('Clear Event:Cache')}}
                    </x-button>
                </form>

                <form action="{{route(route_prefix().'admin.general.cache.settings')}}" method="post">
                    @csrf
                    <input type="hidden" name="type" value="all">
                    <x-button class="success" type="submit" >
                        {{__('Clear All Kind of Cache')}}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
