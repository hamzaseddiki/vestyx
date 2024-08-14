@extends('tenant.frontend.user.dashboard.user-master')
@section('title')
   {{__('Event Payment Logs')}}
@endsection


@section('section')
    @if(count($all_user_jobs) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{__('Job application Info')}}</th>
                    <th scope="col">{{__('Payment Status')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($all_user_jobs as $data)
                    <tr>
                        <td>
                            <div class="user-dahsboard-order-info-wrap">
                                <h5 class="title">{{$data->job?->getTranslation('title',get_user_lang())}}</h5>
                                <div class="div">
                                    <small class="d-block"><strong>{{__('ID')}}</strong>: #{{$data->id}}</small>

                                    <small class="d-block"><strong>{{__('Company Name')}}</strong>: {{$data->job?->getTranslation('company_name',get_user_lang())}}</small>
                                    <small class="d-block"><strong>{{__('Job Location')}}</strong>: {{$data->job?->getTranslation('job_location',get_user_lang())}}</small>
                                    <small class="d-block"><strong>{{__('Job Designation')}}</strong>: {{$data->job?->getTranslation('designation',get_user_lang())}}</small>
                                    <small class="d-block"><strong>{{__('Apply Date')}}</strong>: {{$data->created_at?->format('d-m-Y')}}</small>
                                    <small class="d-block"><strong>{{__('Salary Offer')}}</strong>: {{ amount_with_currency_symbol($data->job?->salary_offer) }}</small>
                                    <small class="d-block"><strong>{{__('Job Type')}}</strong>: {{ \Modules\Job\Enums\WorkingTypeEnum::getText($data->working_type) }}</small>

                                    @if(!empty($data->payment_gateway))
                                       <small class="d-block"><strong>{{__('Application Fee')}}</strong>: {{amount_with_currency_symbol($data->amount)}}</small>
                                    @endif


                                    @if(!empty($data->payment_gateway) && $data->status == 1)
                                        <form action="{{route('tenant.frontend.job.invoice.generate')}}"  method="post">
                                            @csrf
                                            <input type="hidden" name="id" id="invoice_generate_order_field" value="{{$data->id}}">
                                            <button class="btn btn-secondary btn-xs btn-small margin-top-10" type="submit">{{__('Invoice')}}</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td>
                            @if(!empty($data->payment_gateway) && $data->status != 1)
                                <span class="alert alert-warning text-capitalize donation_status d-inline-block">{{ \App\Enums\DonationPaymentStatusEnum::getText($data->status) }}</span>
                                <a href="{{route('tenant.frontend.job.payment',$data->job?->slug)}}" class="btn btn-success btn-sm my-2" target="_blank">{{__('Pay Now')}}</a>
                            @else
                                <span class="alert alert-success text-capitalize donation_status d-inline-block">{{ \App\Enums\DonationPaymentStatusEnum::getText($data->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="blog-pagination">
            {{ $all_user_jobs->links() }}
        </div>
    @else
        <div class="alert alert-warning">{{__('No Jobs Attendance Found')}}</div>
    @endif
@endsection
