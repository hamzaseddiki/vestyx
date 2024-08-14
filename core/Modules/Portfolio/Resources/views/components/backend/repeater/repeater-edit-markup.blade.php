<br>
<div class="iconbox-repeater-wrapper">
    @php
        $faq_items = !empty($donation->faq) ? unserialize($donation->faq,['class' => false]) : ['title' => ['']];
    @endphp
    @forelse($faq_items['title'] as $faq)
        <div class="all-field-wrap">
            <div class="form-group">
                <label for="faq">{{__('Faq Title')}}</label>
                <input type="text" name="faq[title][]" class="form-control" value="{{$faq}}">
            </div>
            <div class="form-group">
                <label for="faq_desc">{{__('Faq Description')}}</label>
                <textarea name="faq[description][]" class="form-control">{{$faq_items['description'][$loop->index] ?? ''}}</textarea>
            </div>
            <div class="action-wrap">
                <span class="add"><i class="las la-plus"></i></span>
                <span class="remove"><i class="las la-trash"></i></span>
            </div>
        </div>
    @empty
        <div class="all-field-wrap">
            <div class="form-group">
                <label for="faq">{{__('Faq Title')}}</label>
                <input type="text" name="faq[title][]" class="form-control" placeholder="{{__('faq title')}}">
            </div>
            <div class="form-group">
                <label for="faq_desc">{{__('Faq Description')}}</label>
                <textarea name="faq[description][]" class="form-control" placeholder="{{__('faq description')}}"></textarea>
            </div>
            <div class="action-wrap">
                <span class="add"><i class="las la-plus"></i></span>
                <span class="remove"><i class="las la-trash"></i></span>
            </div>
        </div>
    @endforelse
</div>
