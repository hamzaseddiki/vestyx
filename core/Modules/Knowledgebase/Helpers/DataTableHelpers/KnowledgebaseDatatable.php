<?php


namespace Modules\Knowledgebase\Helpers\DataTableHelpers;


class KnowledgebaseDatatable
{
    public static function infoColumn($row,$default_lang)
    {
        $output = '<ul class="all_donation_info_column">';
        $output .= '<li><strong>' . __('Title') . ' :</strong> ' . $row->getTranslation('title',$default_lang) . '</li>';
        $output .= '<li><strong>' . __('Created At') . ' :</strong> ' . $row->created_at?->format('d-m-Y') . '</li>';
        $output .= '</li>';
        $output .= '</ul>';

        return $output;
    }
}
