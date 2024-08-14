
    <div class="form-group ecommerce_data">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane"
                        type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">{{__('Product')}}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button"
                        role="tab" aria-controls="profile-tab-pane" aria-selected="false">{{__('Inventory')}}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab"
                        aria-controls="contact-tab-pane" aria-selected="false">{{__('Campaigns')}}</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

                <div class="extra my-3 product_section_parent">
                    <x-fields.switcher name="ecommerce_permission[]" class="product_section_parent_switcher" dataValue="product" label="{{__('Enable/Disable Product Permission')}}"/>
                </div>

                <div class="product_section_child">
                    <x-fields.input type="text" name="product_create_permission" label="{{__('Product Create Permission')}}" info="{{__('If you leave this blank that means it will set as unlimited')}}"/>
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="product_simple_search_permission" label="{{__('Enable/Disable Product Simple Search Permission')}}"/>
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="product_advance_search_permission" label="{{__('Enable/Disable Product Advance Search Permission')}}"/>
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="product_duplication_permission" label="{{__('Enable/Disable Product Duplication Permission')}}"/>
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="product_bulk_delete_permission" label="{{__('Enable/Disable Product Bulk Delete Permission')}}"/>
                </div>

            </div>

            <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

                <div class="extra my-3 inventory_section_parent">
                    <x-fields.switcher name="ecommerce_permission[]" class="inventory_section_parent_switcher" dataValue="inventory" label="{{__('Enable/Disable Inventory Permission')}}"/>
                </div>

                <div class="inventory_section_child">
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="inventory_update_product_permission" label="{{__('Enable/Disable Inventory Update Product Permission')}}"/>
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="inventory_simple_search_permission" label="{{__('Enable/Disable Inventory Simple Search Permission')}}"/>
                    <x-fields.switcher name="ecommerce_permission[]" dataValue="inventory_advance_search_permission" label="{{__('Enable/Disable Inventory Advance Search Permission')}}"/>
                </div>

            </div>

            <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">

                <div class="extra my-3 campaign_section_parent">
                    <x-fields.switcher name="ecommerce_permission[]" class="campaign_section_parent_switcher" dataValue="campaign" label="{{__('Enable/Disable Campaign Permission')}}"/>
                </div>

                <div class="campaign_section_child">
                    <x-fields.input type="text" name="campaign_create_permission" label="{{__('Campaign Create Permission')}}"/>
                </div>

            </div>
        </div>
    </div>
