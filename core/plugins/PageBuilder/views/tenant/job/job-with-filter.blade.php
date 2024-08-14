<style>
    .select-itms .niceSelect {
        border: 1px solid #ddd;
        width: 100%;
        height: 53px;
        background: var(--main-color-one);
        padding: 16px 38px 16px 22px;
        color: #fff;
        margin-bottom: 16px;
        line-height: 19px;
        border-radius: 0;
        margin-bottom: 20px;
        font-size: 16px;
    }
</style>
<div class="eventListing section-padding2">
    <div class="container">
        <!-- Search Bar -->
        <div class="row g-4 justify-content-between mb-40 job_filter">
            <div class="col-lg-6 col-md-6 ">
                <div class="searchBox-wrapper">
                    <!-- Search input Box -->
                   <div class="alert alert-danger search_bottom_message d-none mt-3">{{__('Enter title to search')}}</div>
                    <form action="#" class="searchBox searchBox2 mb-0">
                        <div class="input-form">
                            <input type="text" name="filter_input_search_title" class=" keyup-input filter_input_search_title" placeholder="Job Title or Keyword">
                            <i class="las la-search icon"></i>
                        </div>
                        <div class="input-form location">
                            <input type="text" name="filter_input_search_location" placeholder="Location" class="filter_input_search_location">
                            <i class="las la-map-marker-alt icon"></i>
                        </div>


                        <div class="search-form">
                            <button type="button" class="search filter_search_button">{{__('Search')}}</button>
                        </div>

                    </form>
                </div>
            </div>
            @php
                $all_data = $data['job'];
            @endphp

            <div class="col-lg-5 col-md-5 ">
                <div class="selectItems d-flex justify-content-end">
                    <!-- Select One -->
                    <div class="select-itms mr-20">
                        <select class="niceSelect filter_input_sort_salary" name="filter_sort_salary">
                            <option selected disabled>{{__('Salary range')}}</option>
                            <option value="10000-20000">10K-20K</option>
                            <option value="20000-30000">20K-30K</option>
                            <option value="30000-40000">30K-40K</option>
                            <option value="40000-50000">40K-50K</option>
                            <option value="50000-70000">50K-70K</option>
                            <option value="70000-100000">70K-100K</option>
                        </select>
                    </div>

                    <!-- Select Two -->
                    <div class="select-itms">
                        <select class="niceSelect filter_input_category">
                              <option selected disabled>{{__('Select Category')}}</option>
                            @foreach($data['all_categories'] as $cat)
                                 <option value="{{$cat->id}}" @selected(request('filter_category_id') == $cat->id)>{{$cat->getTranslation('title',get_user_lang())}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <x-job::frontend.job.job-grid :allJob="$data['job']" :searchTerm="$data['search_term']"/>
        </div>



        <!-- Pagination -->
        @if(count($all_data) > 6)
        <div class="row">
            <div class="col-lg-12">
                <div class="pagination mt-60">
                    {{ $all_data->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>


@php
    $route = route('tenant.dynamic.page',request()->path());
@endphp

<form action="{{$route}}" method="get" class="all_filter_form">
    <input type="hidden" class="filter_receieved_search_title" name="filter_search_title">
    <input type="hidden" class="filter_receieved_search_location" name="filter_search_location">
    <input type="hidden" class="filter_receieved_sort_salary" name="filter_sort_salary">
    <input type="hidden" class="filter_receieved_category_id" name="filter_category_id">
</form>


@section('scripts')
    <script>
        $(document).ready(function(){
            let body_main = $('.job_filter');

            setTimeout(function (){
                $('.event_filter_top_message').hide();
            },3000)

            function trigger_form() {
                return $('.all_filter_form').trigger('submit');
            }

            $(document).on('click','.filter_search_button',function(e){
                e.preventDefault();
                let el = $('.filter_input_search_title').val();

                if(el == ''){
                    $('.search_bottom_message').removeClass('d-none');
                    setTimeout(function (){
                        $('.search_bottom_message').addClass('d-none');
                    },2000)
                }else{
                    $('.filter_receieved_search_title').val(body_main.find('.filter_input_search_title').val());
                    $('.filter_receieved_search_location').val(body_main.find('.filter_input_search_location').val());
                     trigger_form();
                }
            })

            $(document).on('change','.filter_input_sort_salary',function(e){
                e.preventDefault();
                $('.filter_receieved_sort_salary').val(body_main.find('.filter_input_sort_salary').val());
                trigger_form();
            })

            $(document).on('change','.filter_input_category',function(e){
                e.preventDefault();
                $('.filter_receieved_category_id').val(body_main.find('.filter_input_category').val());
                 trigger_form();
            })
        });
    </script>
@endsection
