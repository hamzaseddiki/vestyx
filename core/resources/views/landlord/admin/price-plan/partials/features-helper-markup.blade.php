@php
    $check_image = '<img src="'.asset('assets/landlord/frontend/img/icon/check.svg').'" class="icon" alt="image">';
@endphp

@if(!empty($order_details->page_permission_feature))
<li class="single"> {!! $check_image !!} {{__('Page')}} : {{$order_details->page_permission_feature}}</li>
@endif

@if(!empty($order_details->appointment_permission_feature))
    <li class="single"> {!! $check_image !!}  {{__('Appointment')}} : {{$order_details->appointment_permission_feature}}</li>
@endif

@if(!empty($order_details->blog_permission_feature))
<li class="single"> {!! $check_image !!}  {{__('Blog')}} : {{$order_details->blog_permission_feature}}</li>
@endif

@if(!empty($order_details->product_permission_feature))
<li class="single"> {!! $check_image !!} {{__('Product')}} : {{$order_details->product_permission_feature}}</li>
@endif

@if(!empty($order_details->service_permission_feature))
  <li class="single"> {!! $check_image !!} {{__('Service')}} : {{$order_details->service_permission_feature}}</li>
@endif

@if(!empty($order_details->donation_permission_feature))
  <li class="single"> {!! $check_image !!} {{__('Donation')}} : {{$order_details->donation_permission_feature}}</li>
@endif

@if(!empty($order_details->job_permission_feature))
  <li class="single"> {!! $check_image !!} {{__('Job')}} : {{$order_details->job_permission_feature}}</li>
@endif

@if(!empty($order_details->event_permission_feature))
  <li class="single"> {!! $check_image !!} {{__('Event')}} : {{ $order_details->event_permission_feature }}</li>
@endif

@if(!empty($order_details->knowledgebase_permission_feature))
 <li class="single"> {!! $check_image !!} {{__('Article')}} : {{$order_details->knowledgebase_permission_feature}} </li>
@endif

@if(!empty($order_details->portfolio_permission_feature))
  <li class="single"> {!! $check_image !!} {{ __('Portfolio') }} : {{$order_details->portfolio_permission_feature}}</li>
@endif
