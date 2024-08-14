<div class="select-language" id="languages_selector">
    <select class="landlord_languages_selector niceSelect_active">
        @foreach(\App\Facades\GlobalLanguage::all_languages(\App\Enums\StatusEnums::PUBLISH) as $lang)
            <option value="{{$lang->slug}}">{{current(explode('(',$lang->name))}}</option>
        @endforeach
    </select>
</div>

