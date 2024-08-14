<style>
    .selectItems .niceSelect {
        border: 0;
        height: 53px;
        background: var(--main-color-one);
        padding: 16px 38px 16px 22px;
        color: #fff;
        margin-bottom: 16px;
        line-height: 19px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 16px;
        width: auto;
        min-width: 140px;
    }
</style>
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
            {{__('Showing')}} 1-{{$pagination->count()}} {{__('of')}} {{$pagination->total()}} {{__('Results')}}
        </span>



