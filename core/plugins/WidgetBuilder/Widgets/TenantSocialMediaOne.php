<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Illuminate\Support\Str;
use Modules\Service\Entities\ServiceCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;

class TenantSocialMediaOne extends WidgetBase
{

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'newspaper_social_one_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_url',
                    'label' => __('URL')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_number',
                    'label' => __('Number')
                ],

            ]
        ]);


        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();

        $repeater_data = $settings['newspaper_social_one_repeater'];

    $social_markup = '';

    $icon_classes = ['facebook','youtube','instagram','pinterest','twitter','linkedin'];
    foreach ($repeater_data['repeater_title_'.get_user_lang()] ?? []  as $key => $ti) {
            $title = $ti ?? '';
            $title_url = $repeater_data['repeater_title_url_'.get_user_lang()][$key] ?? '';
            $number = $repeater_data['repeater_number_'.get_user_lang()][$key] ?? '';

            $color_condition = $icon_classes[ $key % count($icon_classes)];
            $colo_last_con = $color_condition == 'pinterest' ? 'pintarest' : $color_condition;
            $icon_condition = $icon_classes[ $key % count($icon_classes)];

  $social_markup .= <<<LIST
     <div class="newspaper_sideWidget__followList__single">
        <a class="{$colo_last_con}-bg" href="{$title_url}"> <i class="lab la-{$icon_condition}"></i> </a>
        <div class="newspaper_sideWidget__followList__single__followers">
            <p class="followers">{$number}</p>
            <span class="followers">{$title} </span>
        </div>
    </div>

LIST;
        }


 return <<<HTML
    <div class="newspaper_sideWidget__item">
        <div class="newspaper_sideWidget__follower">
            <div class="newspaper_sideWidget__followList">
               {$social_markup}
            </div>
        </div>
    </div>
HTML;
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function widget_title()
    {
        return __('Social Media');
    }
}
