@extends('tenant.frontend.frontend-page-master')
@section('title')
    {!! $category_name !!}
@endsection
@section('page-title')
    {!! __('Category: ').$category_name !!}
@endsection
@section('content')

    <section class="blog-content-area" data-padding-top="100" data-padding-bottom="100">
        <div class="container">
            <div class="row">

                <div class="col-lg-8">

                    <div class="row">
                        <x-knowledgebase::frontend.knowledgebase.knowledgebase-grid :allKnowledgebaseCategory="$all_knowledgebase" :searchTerm="$category_name"/>
                    </div>

                    <div class="col-lg-12">
                         {!! $all_knowledgebase->links() !!}
                    </div>

                </div>

                <div class="col-lg-4">
                    {!! render_frontend_sidebar('knowledgebase_sidebar', ['column' => false]) !!}
                </div>
            </div>
        </div>
    </section>
@endsection
