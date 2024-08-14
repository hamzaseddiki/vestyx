<div id="ship_return">
    <div class="return-tab-wrapper">
        <div class="row align-items-center">
            <div class="col-lg-12 mt-4">
                <div class="single-return-tab">
                    <div class="single-return-tab-content">
                        {!! $product?->return_policy?->getTranslation('shipping_return_description',get_user_lang()) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
