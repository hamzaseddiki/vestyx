<?php


namespace App\Helpers;

use Illuminate\Support\Str;
use function abort;
use function auth;
use function request;
use function route;

class MenuWithPermission
{
    private array $parents = [];
    private array $menus_items = [];

    public function add_menu_item(string $id, array $args = [])
    {
        if (empty($args)) {
            return;
        }

        $this->menus_items[$id] = [
            'route' => $args['route'],
            'label' =>  $args['label'],
            'parent' => $args['parent'],
            'class' =>  $args['class'] ?? '',
            'permissions' => $args['permissions'] ?? [],
            'icon' => $args['icon'] ?? '',
        ];
        if (isset($args['parent']) && !empty($args['parent'])) {
            $this->parents[] = $args['parent'];
        }
    }

    public function render_menu_items(): string
    {
        $output = '';
        $all_menu_items = $this->menus_items;

        foreach ($all_menu_items as $id => $item) {
            if (!$this->has_permission_to_view($item)){
                continue;
            }

            if (!$this->has_route($item) && !$this->has_sub_menu_item($id)) {
                abort(405, 'Route name required to render menu');
            }
            if ($this->is_submenu_item($item) ){
                continue;
            }
            $output = $this->get_li_markup($id,$item,$output);
            //  make a private method to fix code duplication issue

        }
        return $output;
    }

    private function render_sub_menus($id, $output): string
    {
        $all_menu_items = $this->get_all_submenu_by_parent_id($id);
        $output .= '<div class="collapse" id="'.Str::slug(strip_tags($id)).'">';

        $output .= '<ul class="nav flex-column sub-menu">';
        foreach ($all_menu_items as $submenu_id => $sub_menu) {
            $output .= $this->render_single_submenu_item($submenu_id, $sub_menu);

            if ($this->is_active_menu_item($sub_menu)) {
                if (isset($sub_menu['parent']) && !empty($sub_menu['parent'])){
                    $output = str_replace(
                        array(
                            'submenu-item-' . Str::slug($sub_menu['parent']),
                            'href="#'. Str::slug($sub_menu['parent']) .'" aria-expanded="false"',
                            'class="collapse" id="'.Str::slug(strip_tags($id)).'"'
                        ), array(
                        'nav-item active submenu-item-' . Str::slug($sub_menu['parent']),
                        'href="#' . Str::slug($sub_menu['parent']). '" aria-expanded="true"',
                        'class="collapse show" id="'.Str::slug(strip_tags($id)).'"'
                    ),
                        $output
                    );
                }
            }
        }
        $output .= '</ul></div>';



        return $output;
    }

    private function is_active_menu_item($items): bool
    {
        $route_name = $items['route'];
        if (!$this->has_route($items)) {
            return false;
        }
        return (bool)request()->routeIs($route_name);
    }

    private function has_permission_to_view($items): bool
    {
        $permissions = $items['permissions'];
        $admin_details = auth('admin')->user();

        switch ($permissions) {
            case(is_array($permissions)):
                return (bool) optional($admin_details)->canany($permissions);
                break;
            case(is_string($permissions)):
                return (bool) optional($admin_details)->can($permissions);
                break;
            default:
                return false;
                break;
        }
    }

    private function has_route($item): bool
    {
        return isset($item['route']) && !empty($item['route']);
    }

    private function has_icon($item): bool
    {
        return isset($item['icon']) && !empty($item['icon']);
    }

    private function has_label($item): bool
    {
        return isset($item['label']) && !empty($item['label']);
    }

    private function has_sub_menu_item($id): bool
    {
        return in_array($id,$this->parents);
    }

    private function get_all_submenu_by_parent_id($id): array
    {
        $all_menu_items = $this->menus_items;
        $all_submenu_items = [];
        foreach ($all_menu_items as $item_id => $item) {
            if (isset($item['parent']) && $item['parent'] === $id) {
                $all_submenu_items[$item_id] = $item;
            }
        }
        return $all_submenu_items;
    }

    private function render_single_submenu_item($submenu_id, $sub_menu): string
    {

        if (!$this->has_permission_to_view($sub_menu)) {
            return '';
        }
        if (!$this->has_route($sub_menu)) {
            abort(405, 'Route name required to render submenu');
        }
        if (!$this->is_submenu_item($sub_menu)){
            return '';
        }
        $output = '';
        $output .= $this->get_li_markup($submenu_id,$sub_menu,$output);
        return $output;
    }

    private function is_submenu_item($item) : bool
    {
        return isset($item['parent']) && !is_null($item['parent']);
    }

    private function get_li_markup($id, $item, string $output) : string
    {
        $output .= '<li';
        $li_classes = ['nav-item'];
        if ($this->is_active_menu_item($item)) {
            $li_classes[] = 'active';
            if (isset($item['parent']) && !empty($item['parent'])){
                $output = str_replace('submenu-item-'.Str::slug($item['parent']),'nav-item active submenu-item-'.Str::slug($item['parent']),$output);
            }
        }
        $route = $item['route'] === '#' ?: route($item['route']);
        if ($this->has_sub_menu_item($id)) {
            $li_classes[] = 'submenu-item-'.Str::slug($id);
            $route = 'javascript:void(0)';
        }
        $output.= ' class="'.implode(' ',$li_classes).' '.($item['class'] ?? '').' "';
        $output .= '>';

        $output .= '<a class="nav-link"';

        if ($this->has_sub_menu_item($id)) {
            $output .= 'data-bs-toggle="collapse"' ;
            $route = '#'.Str::slug(strip_tags($id));
        }
        $output .= 'href="'.$route.'"';
        $output .= ' aria-expanded="false">';

        if ($this->has_label($item)) {
            $output .= '<span class="menu-title">' . $item['label'] . '</span>';
        }
        if ($this->has_sub_menu_item($id)) {
            $output .= '<i class="menu-arrow"></i>';
        }

        if ($this->has_icon($item)) {
            $output .= '<i class="' . $item['icon'] . ' menu-icon"></i>';
        }
        $output .= '</a>';
        if ($this->has_sub_menu_item($id)) {
            $output = $this->render_sub_menus($id, $output);
        }
        $output .= '</li>';

        return $output;
    }
}

