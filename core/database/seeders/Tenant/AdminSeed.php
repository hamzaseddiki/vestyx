<?php

namespace Database\Seeders\Tenant;

use App\Jobs\PlaceOrderMailJob;
use App\Jobs\TenantCredentialJob;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AdminSeed extends Seeder
{
    public static function run()
    {
        $username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';
        $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';

        $random_pass = Str::random(8);
        //Storing random pass in session for mailing
        \session()->put('random_password_for_tenant',$random_pass);
        $password_condition = !empty(get_static_option_central('tenant_seeding_password_status')) ? $random_pass : $raw_pass;

        $admin = Admin::create([
            'name' => 'Test User',
            'username' => $username,
            'email' => 'test@test.com',
            'password' => Hash::make($password_condition),
            'image' => 11,
            'email_verified' => 1
        ]);

        $admin->assignRole('Super Admin');

    }
}
