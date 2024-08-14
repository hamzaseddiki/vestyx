@extends('tenant.frontend.frontend-page-master')

@section('title')
    {{ $category_name}}
@endsection

@section('page-title')
    {{__('Category: ').$category_name}}
@endsection

@section('content')
    <section class="blog-content-area" data-padding-top="110" data-padding-bottom="110">
        <div class="container">
            <div class="row">

                <div class="col-lg-8">
                    <div class="row">
                        <x-event::frontend.event.event-grid :allEvent="$all_event" :searchTerm="$category_name " :col="6"/>
                    </div>
                    <div class="col-lg-12">
                            {!! $all_event->links() !!}
                    </div>
                </div>

                <div class="col-lg-4">
                    {!! render_frontend_sidebar('event_sidebar', ['column' => false]) !!}
                </div>
            </div>
        </div>
    </section>
@endsection
