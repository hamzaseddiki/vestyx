@extends('tenant.frontend.frontend-page-master')
@section('title')
    {{ $search_term}}
@endsection
@section('page-title')
    {{__('Search For: ').$search_term}}
@endsection
@section('content')

    <section class="blog-content-area" data-padding-top="110" data-padding-bottom="110">
        <div class="container">
            <div class="row">

                <div class="col-xxl-8 col-xl-8 col-lg-8">
                    <div class="row">
                        <x-job::frontend.job.job-grid :allJob="$all_job" :searchTerm="$search_term ?? '' " :col="6"/>
                    </div>
                    <div class="col-lg-12">
                        <nav class="pagination-wrapper" aria-label="Page navigation ">
                            {{$all_job->links()}}
                        </nav>
                    </div>
                </div>

                <div class="col-xxl-4 col-xl-4 col-lg-5 col-md-6">
                    {!! render_frontend_sidebar('job_sidebar', ['column' => false]) !!}
                </div>


            </div>
        </div>
    </section>
@endsection
