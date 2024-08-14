@php
    $current_lang = \App\Facades\GlobalLanguage::user_lang_slug();
@endphp

    <!--Contact Area S t a r t-->
<div class="contactArea" data-padding-top="{{$data['padding_top']}}" data-padding-bottom="{{$data['padding_bottom']}}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="small-tittle mb-40">
                    <h2 class="tittle p-0">{{$data['title'] ?? 'Send us an Email'}}</h2>
                </div>
            </div>
        </div>
        <div class="row g-4 justify-content-between">
            <div class="col-lg-6">
                @php
                    $height = $data['map_height'].'px';
                @endphp
                <div class="mapArea">
                    <div class="mapWrapper" style="height: {{$height}}">
                        {!! $data['location'] !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                @if(!empty($data['custom_form_id']))
                    @php $form_details = \App\Models\FormBuilder::find($data['custom_form_id']); @endphp
                    {!! \App\Helpers\FormBuilderCustom::render_form(optional($form_details)->id,null,null,'infoTitle') !!}
                @endif
            </div>
        </div>

    </div>
</div>
<!-- End-of Contact -->



@section('scripts')
    <x-custom-js.contact-form-store/>
@endsection

