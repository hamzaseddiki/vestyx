<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\misc;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class TeamMemberTwo extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/team-member-02.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'team_member_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_name',
                    'label' => __('Name')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_designation',
                    'label' => __('Designation')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_facebook_url',
                    'label' => __('Facebook URL')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_twitter_url',
                    'label' => __('Twitter URL')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_instagram_url',
                    'label' => __('Instagram URL')
                ],

                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],

            ]
        ]);


        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $user_lang = GlobalLanguage::default_slug();
        $title = $this->setting_item('title_'.$user_lang) ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $repeater_data = $this->setting_item('team_member_repeater');

        $data = [
            'title'=> $title,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.Common.misc.team-member-two',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Team Member : 02');
    }
}
