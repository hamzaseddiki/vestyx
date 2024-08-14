@php
    if(!isset($selectedDeliveryOption)){
        $selectedDeliveryOption = [];
    }
@endphp

<div class="general-info-wrapper px-4">
    <h4 class="dashboard-common-title-two mb-4">{{ __("Delivery Options") }}</h4>
    <div class="general-info-form mt-0 mt-lg-4">
        <div class="d-flex flex-wrap gap-2">
            <input type="hidden" value="{{ implode(" , ", $selectedDeliveryOption) }}" name="delivery_option" class="delivery-option-input" />

            @foreach($deliveryOptions as $deliveryOption)
                <div class="delivery-item flex-wrap d-flex {{ in_array($deliveryOption->id, $selectedDeliveryOption) ? "active" : "" }}" data-delivery-option-id="{{ $deliveryOption->id }}">
                    <div class="icon">
                        <i class="{{ $deliveryOption->icon }}"></i>
                    </div>
                    <div class="content">
                        <h6 class="title">{{ $deliveryOption->getTranslation('title',$defaultLang) }}</h6>
                        <p>{{ $deliveryOption->getTranslation('sub_title',$defaultLang) }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
