<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Http\Controllers\Controller;

class ShopCreationController extends Controller
{
    private const BASE_VIEW_PATH = 'tenant.frontend.';

    public function show_theme($id)
    {
        dd($id);
    }
}
