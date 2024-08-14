@extends(route_prefix().'admin.admin-master')
@section('title') {{__(' Contact Message Details')}} @endsection

@section('style')
    <style>
        ul li{
            list-style-type: none;
        }
    </style>
@endsection

@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                    <x-slot name="left">
                        <h4 class="card-title mb-5">{{__(' Contact Message Details')}}</h4>
                        <x-link-with-popover url="{{route(route_prefix().'admin.contact.message.all')}}" extraclass="ml-3">
                            {{__('All Message')}}
                        </x-link-with-popover>

                    </x-slot>
                </x-admin.header-wrapper>
            @php
                $attachments = json_decode($message['attachment']);
                $data_content = json_decode($message['fields']);
            @endphp

                    <div class="main-content">
                        <h6>{{__('Basic Information')}}</h6>
                       <ul>
                           <li><strong>{{__('ID : ')}} </strong>{{$message->id}}</li>
                           <li><strong>{{__('Date : ')}}</strong>{{$message->created_at?->format('d-m-Y')}}</li>

                           @foreach($attachments ?? [] as $key => $val)
                           <li>
                               <strong>{{__('Attachment : ')}}</strong>
                               <a class="my-1" download="" href="{{asset($val)}}" target="_blank">{{$val}}</a>
                           </li>
                           @endforeach
                       </ul>


                   <h6 class="mt-5">{{__('Details Information')}}</h6>
                        <ul>
                            @foreach($data_content ?? [] as $key => $val)
                            <li>{{$key}}<strong> {{__(' : ')}} {{$val}}</strong></li>
                             @endforeach
                        </ul>
                    </div>


            </div>
        </div>
    </div>
@endsection

