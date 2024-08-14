<?php


namespace Plugins\PageBuilder\Fields;


use Plugins\PageBuilder\Helpers\Traits\FieldInstanceHelper;
use Plugins\PageBuilder\PageBuilderField;

class Textarea extends PageBuilderField
{
    use FieldInstanceHelper;

    /**
     * render field markup
     * */
    public function render()
    {
        $output = '';
        $output .= $this->field_before();
        $output .= $this->label();
        $output .= '<textarea name="'.$this->name().'"  placeholder="'.$this->placeholder().'"  cols="10" rows="5" class="'.$this->field_class().'">'.$this->value().'</textarea>';
        $output .= $this->field_after();

        return $output;
    }
}
