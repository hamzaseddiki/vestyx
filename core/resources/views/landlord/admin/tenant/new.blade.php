@php
    $route_name ='landlord';
@endphp
@extends($route_name.'.admin.admin-master')
    @section('title') {{__('Add New User')}} @endsection
@section('style')
    <x-media-upload.css/>
@endsection
@section('content')
    <div class="col-12 stretch-card">
        <div class="card">
            <div class="card-body">
                <x-admin.header-wrapper>
                <x-slot name="left">
                <h4 class="card-title mb-4">{{__('Add New User')}}</h4>
                </x-slot>

                <x-slot name="right">
                    <a href="{{route('landlord.admin.tenant')}}" class="btn btn-info btn-sm">{{__('All Users')}}</a>
                </x-slot>

                </x-admin.header-wrapper>
                <x-error-msg/>
                <x-flash-msg/>

                <form class="forms-sample"  action="{{route('landlord.admin.tenant.new')}}" method="post">
                    @csrf
                    <x-fields.input type="text" name="name" class="form-control" placeholder="{{__('name')}}" label="{{__('Name')}}"/>
                    <x-fields.input type="text" name="username" class="form-control" placeholder="{{__('username')}}" label="{{__('Username')}}"/>
                    <x-fields.input type="email" name="email" class="form-control" placeholder="{{__('email')}}" label="{{__('Email')}}"/>
                    <x-fields.input type="text" name="mobile" class="form-control" placeholder="{{__('mobile')}}" label="{{__('Mobile')}}"/>



                   <div class="form-group">
                       <label for="">{{__('Country')}}</label>
                       <div class="col-md-12">
                           <select name="country" class="form-control register_countries">
                               <option disabled="" selected>{{__('Select a country')}}</option>
                               @foreach($countries as $country)
                                   <option value="{{$country->id}}">{{$country->name}}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>


                    <x-fields.input type="text" name="city" class="form-control" placeholder="{{__('city')}}" label="{{__('City')}}"/>
                    <x-fields.input type="text" name="state" class="form-control" placeholder="{{__('state')}}" label="{{__('State')}}"/>
                    <x-fields.input type="text" name="company" class="form-control" placeholder="{{__('company')}}" label="{{__('Company')}}"/>
                    <x-fields.input type="text" name="address" class="form-control" placeholder="{{__('address')}}" label="{{__('Address')}}"/>
                    <x-fields.input type="password" name="password" class="form-control"  label="{{__('Password')}}"/>
                    <x-fields.input type="password" name="password_confirmation" class="form-control"  label="{{__('Confirm Password')}}"/>

                    <button type="submit" class="btn btn-gradient-primary me-2 mt-5">{{__('Submit')}}</button>

                </form>


            </div>
        </div>
    </div>

    <x-media-upload.markup/>
@endsection

@section('scripts')
    <script>

        $(document).ready(function (){

            function removeTags(str) {
                if ((str===null) || (str==='')){
                    return false;
                }
                str = str.toString();
                return str.replace( /(<([^>]+)>)/ig, '');
            }
            $(document).on('keyup paste change','input[name="subdomain"]',function (e){

                let value = removeTags($(this).val()).toLowerCase().replace(/\s/g, "-");
                $(this).val(value)
                if(value.length < 1) {
                    return;
                }
                let msgWrap = $('#subdomain-wrap');
                msgWrap.html('');
                msgWrap.append('<span class="text-warning">{{__('availability checking..')}}</span>');
                axios({
                    url : "{{route('landlord.subdomain.check')}}",
                    method : 'post',
                    responseType: 'json',
                    data : {
                        subdomain: value
                    }
                }).then(function(res){
                    msgWrap.html('');
                    msgWrap.append('<span class="text-success"> '+ value+ ' {{__('is available')}}</span>');
                    $('#login_button').attr('disabled',false)
                }).catch(function (error){
                    var responseData = error.response.data.errors;
                    msgWrap.html('');
                    msgWrap.append('<span class="text-danger"> '+ responseData.subdomain+ '</span>');
                    $('#login_button').attr('disabled',true)
                });

            }); //subdomain check

        }); // end document ready

    </script>
    <x-media-upload.js/>
@endsection

