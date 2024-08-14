@foreach($sub_category as $item)
    <option value="{{ $item->id }}">{{ $item->getTranslation('name',$default_lang) }}</option>
@endforeach
