<?php

namespace Database\Seeders\Tenant;

use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class RolePermissionSeed extends Seeder
{

    public static function process_seeding()
    {

        $permissions = [
            "form-submission",
            "page-list",
            "page-create",
            "page-edit",
            "page-delete",
            "price-plan-list",
            "price-plan-create",
            "price-plan-edit",
            "price-plan-delete",
            "package-order-all-order",
            "package-order-pending-order",
            "package-order-progress-order",
            "package-order-complete-order",
            "package-order-success-order-page",
            "package-order-cancel-order-page",
            "package-order-order-page-manage",
            "package-order-order-report",
            "package-order-payment-logs",
            "package-order-payment-report",
            "package-order-delete",
            "package-order-edit",
            "testimonial-list",
            "testimonial-create",
            "testimonial-edit",
            "testimonial-delete",
            "brand-list",
            "brand-create",
            "brand-edit",
            "brand-delete",
            "blog-category-list",
            "blog-category-create",
            "blog-category-edit",
            "blog-category-delete",
            "blog-list",
            "blog-create",
            "blog-edit",
            "blog-delete",
            "blog-settings",
            "blog-comments",
            "service-category-list",
            "service-category-create",
            "service-category-edit",
            "service-category-delete",
            "service-list",
            "service-create",
            "service-edit",
            "service-delete",
            "service-settings",
            "service-comments",

            "donation-list",
            "donation-create",
            "donation-edit",
            "donation-delete",

            "event-list",
            "event-create",
            "event-edit",
            "event-delete",

            "job-list",
            "job-create",
            "job-edit",
            "job-delete",

            "knowledgebase-list",
            "knowledgebase-create",
            "knowledgebase-edit",
            "knowledgebase-delete",

            "donation-activities-list",
            "donation-activities-create",
            "donation-activities-edit",
            "donation-activities-delete",

            "portfolio-list",
            "portfolio-create",
            "portfolio-edit",
            "portfolio-delete",

            "image-gallery-list",
            "image-gallery-create",
            "image-gallery-edit",
            "image-gallery-delete",

            "attrtibute-list",
            "attrtibute-create",
            "attrtibute-edit",
            "attrtibute-delete",

            "campaign-list",
            "campaign-create",
            "campaign-edit",
            "campaign-delete",

            "inventory-list",
            "inventory-create",
            "inventory-edit",
            "inventory-delete",

            "product-list",
            "product-create",
            "product-edit",
            "product-delete",

            "country-list",
            "country-create",
            "country-edit",
            "country-delete",

            "shipping-list",
            "shipping-create",
            "shipping-edit",
            "shipping-delete",

            "coupon-list",
            "coupon-create",
            "coupon-edit",
            "coupon-delete",

            "tax-list",
            "tax-create",
            "tax-edit",
            "tax-delete",

            "badge-list",
            "badge-create",
            "badge-edit",
            "badge-delete",
            "product-order",

            "form-builder",
            "widget-builder",
            "general-settings-page-settings",
            "general-settings-global-navbar-settings",
            "general-settings-global-footer-settings",
            "general-settings-site-identity",
            "general-settings-application-settings",
            "general-settings-basic-settings",
            "general-settings-color-settings",
            "general-settings-typography-settings",
            "general-settings-seo-settings",
            "general-settings-payment-settings",
            "general-settings-third-party-script-settings",
            "general-settings-smtp-settings",
            "general-settings-custom-css-settings",
            "general-settings-custom-js-settings",
            "general-settings-database-upgrade-settings",
            "general-settings-cache-clear-settings",
            "general-settings-license-settings",
            "language-list",
            "language-create",
            "language-edit",
            "language-delete",
            "menu-manage",
            "topbar-manage",
            "other-settings",
            "newsletter-list",
            "newsletter-create",
            "newsletter-edit",
            "newsletter-delete",
            "support-ticket-list",
            "support-ticket-create",
            "support-ticket-edit",
            "support-ticket-delete",
            "support-ticket-department-list",
            "support-ticket-department-create",
            "support-ticket-department-edit",
            "support-ticket-department-delete",

            "appointment-category-list",
            "appointment-category-create",
            "appointment-category-edit",
            "appointment-category-delete",

            "appointment-sub-category-list",
            "appointment-sub-category-create",
            "appointment-sub-category-edit",
            "appointment-sub-category-delete",

            "appointment-list",
            "appointment-create",
            "appointment-edit",
            "appointment-delete",

            "sub-appointment-list",
            "sub-appointment-create",
            "sub-appointment-edit",
            "sub-appointment-delete",

            "appointment-day-type",
            "appointment-days",
            "appointment-schedule",
            "appointment-payment-log",
            "appointment-report",
            "appointment-settings",
        ];

        foreach ($permissions as $permission){
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission,'guard_name' => 'admin']);
        }
        $demo_permissions = [];
        $role = Role::updateOrCreate(['name' => 'Super Admin','guard_name' => 'admin'],['name' => 'Super Admin','guard_name' => 'admin']);
        $role->syncPermissions($demo_permissions);
    }
}
