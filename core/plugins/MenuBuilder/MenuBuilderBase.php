<?php

namespace Plugins\MenuBuilder;


abstract class MenuBuilderBase
{
    abstract function static_pages_list();
    abstract function register_dynamic_menus();
}
