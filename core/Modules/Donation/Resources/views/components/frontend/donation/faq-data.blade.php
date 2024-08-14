<section class="faqArea-global">
        <div class="row">
            <div class="col-xl-12">
                <div class="collapse-wrapper">
                    @php
                         $faq_items = !empty($donation->faq) ? unserialize($donation->faq,['class' => false]) : [];
                         $rand_number = rand(9999,99999999);
                         $display_block_check =  $faq_items['title'][0] != null ? 'block' : 'none';
                    @endphp
                    <div class="accordion-{{$rand_number}}" id="accordionExample">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="singleTab-items-global" id="tab-1" style="display:{{$display_block_check}};">
                                    @foreach($faq_items['title'] as $faq)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne-{{$loop->index}}">
                                                <button class="accordion-button {{ $loop->iteration == 1 ? '' : 'collapsed' }}" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapseOne-{{$loop->index}}"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                        {{$faq}}
                                                </button>
                                            </h2>
                                            <div id="collapseOne-{{$loop->index}}"
                                                 class="accordion-collapse collapse {{$loop->iteration == 1 ? 'show' : ''}}"
                                                 aria-labelledby="headingOne-{{$loop->index}}"
                                                 data-bs-parent="#accordionExample">

                                                <div class="accordion-body">
                                                    <p>{{$faq_items['description'][$loop->index] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                     @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</section>
