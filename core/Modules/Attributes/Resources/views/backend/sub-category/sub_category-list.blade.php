@foreach($sub_category as $item)
    <li data-value="{{ $item->id }}" class="option">{{ $item->getTranslation('name',$default_lang) }}</li>
@endforeach
