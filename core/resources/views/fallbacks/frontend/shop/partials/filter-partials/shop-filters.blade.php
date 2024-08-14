
        <div class="selectItems f-right shop-nice-select">
            <select class="niceSelect sorting_shop_page">
                <option value="3"> {{__('Sort By Date')}} </option>
                <option value="1"> {{__('Sort By Name')}} </option>
                <option value="2"> {{__('Sort By Popularity')}} </option>
                <option value="4"> {{__('Lowest to Highest')}} </option>
                <option value="5"> {{__('Highest to Lowest')}} </option>
            </select>

        </div><br>

        <span class="showing-results color-light">
            {{__('Showing')}} 1-{{$pagination->count()}} {{__('of')}} {{$pagination->total()}} {{__('results')}}
        </span>



