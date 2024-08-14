@extends('tenant.frontend.frontend-page-master')
@section('content')
    @include('tenant.frontend.partials.pages-portion.dynamic-page-builder-part',['page_post' => $page_post])
@endsection
