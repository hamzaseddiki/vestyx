<div class="chartBar mb-50">
    <div class="eventsInfo mb-20">
        <div class="small-tittle mb-0">
            <h3 class="tittle lineStyleOne">{{get_static_option('event_chart_area_'.get_user_lang().'_title',__('Ticket Sales'))}}</h3>
        </div>
        <h6>{{__('last 7 Days')}}</h6>
    </div>
    <div class="line-charts">
        <canvas id="line-chart"></canvas>
    </div>
</div>
