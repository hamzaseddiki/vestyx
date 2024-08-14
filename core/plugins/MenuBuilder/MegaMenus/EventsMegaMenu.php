<?php

namespace Plugins\MenuBuilder\MegaMenus;

use Plugins\MenuBuilder\MegaMenuBase;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;

class EventsMegaMenu extends MegaMenuBase
{

    function model(){
        return 'Modules\Event\Entities\Event';
    }
    function render($ids, $lang, $settings)
    {
        //it will have all html markup for the mega menu
        $ids = explode(',',$ids);
        $output = '';
        if (empty($ids)){
            return $output;
        }
        $output .= $this->body_start();

        $mega_menu_items = Event::whereIn('id',$ids)->get()->groupBy('category_id');
        if ($settings['sort_by'] === 'asc'){
            $mega_menu_items->sortBy($settings['sort']);
        }else {
            $mega_menu_items ->sortByDesc($settings['sort']);
        }
        foreach ($mega_menu_items as $cat => $posts) {
            $output .= '<div class="col-lg-3 col-md-6">' ."\n";
            $output .= '<div class="xg-mega-menu-single-column-wrap">'."\n";
            if (!empty($settings['category_status'])){
                $output .= '<h4 class="mega-menu-title">' . $this->category($cat). '</h4>'."\n";
            }
            $output .= '<ul>'."\n";
            foreach ($posts as $post) {
                $output .= '<li><a href="'.route(route_prefix().$this->route(),$post->slug).'">' . $post->title . '</a></li>'."\n";
            }
            $output .= '</ul>'."\n";
            $output .= '</div>'."\n";
            $output .= '</div>'."\n";
        }

        $output .= $this->body_end();
        // TODO: return all makrup data for render it to frontend
        return $output;
    }
    function slug()
    {
        // TODO: Implement name() method.
        return 'events_page_slug';
    }


    function category($id)
    {
        $category = EventCategory::where(['id' => $id])->first();
        return $category->title ?? __('Uncategorized');
    }

    function route()
    {
        // TODO: Implement route() method.
        return 'frontend.events.single';
    }

    function routeParams()
    {
        // TODO: Implement routeParams() method.
        return ['slug'];
    }

    function name()
    {
        // TODO: Implement name() method.
        return 'events_page_[lang]_name';
    }
    function enable()
    {
        // TODO: Implement enable() method.
        return get_static_option('events_module_status');
    }
    function query_type()
    {
        // TODO: Implement query_type() method.
        return 'old_lang'; // old_lang|new_lang
    }
    function title_param()
    {
        // TODO: Implement title_param() method.
        return 'title';
    }
}
