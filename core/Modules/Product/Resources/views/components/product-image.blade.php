<div class="image-product-wrapper">
    @if(isset($product))
        <x-fields.media-upload :id="$product->image_id" :title="__('Feature Image')" :name="'image_id'"
                               :dimentions="'200x200'"/>

        @php
            if (!is_null($product->gallery_images))
            {
                $image_arr = optional($product->gallery_images)->toArray();
            $gallery = '';
            foreach ($image_arr as $key => $arr)
                {
                    $gallery .= $arr['id'];
                    if ($key != count($image_arr)-1)
                        {
                            $gallery .= '|';
                        }
                }
            }
        @endphp
        <x-landlord-others.edit-media-upload-gallery :label="'Image Gallery'" :name="'product_gallery'"
                                                     :value="$gallery ?? ''"/>
    @else
        <x-fields.media-upload :title="__('Feature Image')" :name="'image_id'" :dimentions="'200x200'"/>
        <x-landlord-others.edit-media-upload-gallery :label="'Image Gallery'" :name="'product_gallery'"
                                                     :value="$gallery ?? ''"/>
    @endif
</div>
