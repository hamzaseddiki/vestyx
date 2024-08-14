<?php


namespace Plugins\PageBuilder\Fields;


use Plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use Plugins\PageBuilder\PageBuilderField;

class Notice extends PageBuilderField
{
    use FieldInstanceHelper;

    /**
     * render field markup
     * */
    public function render()
    {
        // TODO: Implement render() method.
        return  '<div class="alert alert-'.$this->args['type'].'">'.$this->args['text'].'</div>';
    }
}
