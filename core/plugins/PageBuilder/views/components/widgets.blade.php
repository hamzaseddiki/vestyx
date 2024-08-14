<div class="search-wrap">
    <div class="form-group">
        <input type="text" class="form-control" id="search_addon_field" placeholder="{{__('Search Addon')}}" name="s">
    </div>
</div>
<div class="all-addons-wrapper">
    <ul id="sortable_02" class="available-form-field all-widgets sortable_02">
        @if(isset($type) && $type === 'tenant')
            {!! \Plugins\PageBuilder\PageBuilderSetup::get_tenant_admin_panel_widgets() !!}
        @else
            {!! \Plugins\PageBuilder\PageBuilderSetup::get_admin_panel_widgets() !!}
        @endif

    </ul>
</div>
