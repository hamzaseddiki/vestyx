<?php

use App\Http\Controllers\Landlord\Admin\AdminRoleManageController;
use App\Http\Controllers\Landlord\Admin\BrandController;
use App\Http\Controllers\Landlord\Admin\ContactMessageController;
use App\Http\Controllers\Landlord\Admin\CustomDomainController;
use App\Http\Controllers\Landlord\Admin\CustomFormBuilderController;
use App\Http\Controllers\Landlord\Admin\Error404PageManage;
use App\Http\Controllers\Landlord\Admin\FaqCategoryController;
use App\Http\Controllers\Landlord\Admin\FormBuilderController;
use App\Http\Controllers\Landlord\Admin\GeneralSettingsController;
use App\Http\Controllers\Landlord\Admin\LandlordAdminController;
use App\Http\Controllers\Landlord\Admin\LanguagesController;
use App\Http\Controllers\Landlord\Admin\MaintainsPageController;
use App\Http\Controllers\Landlord\Admin\MediaUploaderController;
use App\Http\Controllers\Landlord\Admin\MenuController;
use App\Http\Controllers\Landlord\Admin\OrderManageController;
use App\Http\Controllers\Landlord\Admin\PageBuilderController;
use App\Http\Controllers\Landlord\Admin\PagesController;
use App\Http\Controllers\Landlord\Admin\PricePlanController;
use App\Http\Controllers\Landlord\Admin\TenantExceptionController;
use App\Http\Controllers\Landlord\Admin\TenantManageController;
use App\Http\Controllers\Landlord\Admin\ThemeManageController;
use App\Http\Controllers\Landlord\Admin\WidgetsController;
use Modules\CountryManage\Http\Controllers\Tenant\Admin\CountryManageController;
use Modules\SupportTicket\Http\Controllers\Landlord\Admin\SupportDepartmentController;
use Modules\SupportTicket\Http\Controllers\Landlord\Admin\SupportTicketController;
use \App\Http\Controllers\Landlord\Admin\NotificationController;
use \App\Http\Controllers\Landlord\Admin\CouponManageController;
use App\Http\Controllers\Landlord\Admin\SeederSettingsController;

/* ------------------------------------------
     LANDLORD ADMIN ROUTES
-------------------------------------------- */

Route::group(['middleware' => ['auth:admin', 'adminglobalVariable', 'setlang'], 'prefix' => 'admin-home'], function () {
    /* ------------------------------------------
        ADMIN DASHBOARD ROUTES
    -------------------------------------------- */
    Route::controller(LandlordAdminController::class)->group(function () {
        Route::get('/', 'dashboard')->name('landlord.admin.home');
        Route::get('/health', 'health')->name('landlord.admin.health');
        Route::get('/edit-profile', 'edit_profile')->name('landlord.admin.edit.profile');
        Route::get('/change-password', 'change_password')->name('landlord.admin.change.password');
        Route::post('/edit-profile', 'update_edit_profile');
        Route::post('/change-password', 'update_change_password');
    });

    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

    /* ------------------------------------------
     PAGES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(PagesController::class)->name('landlord.')->prefix('pages')->group(function () {
        Route::get('/', 'all_pages')->name('admin.pages');
        Route::get('/new', 'create_page')->name('admin.pages.create');
        Route::post('/new', 'store_new_page');
        Route::get('/edit/{id}', 'edit_page')->name('admin.pages.edit');
        Route::get('/page-builder/{id}', 'page_builder')->name('admin.pages.builder');
        Route::post('/update', 'update')->name('admin.pages.update');
        Route::post('/delete/{id}', 'delete')->name('admin.pages.delete');
        Route::get('/download/{id}', 'download')->name('admin.pages.download');
        Route::post('/upload', 'upload')->name('admin.pages.upload');
    });

    /* ------------------------------------------
     BLOG MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(\Modules\Blog\Http\Controllers\Tenant\Admin\BlogController::class)->prefix('blog')->name('landlord.')->group(function () {
        Route::get('/', 'index')->name('admin.blog');
        Route::get('/new', 'new_blog')->name('admin.blog.new');
        Route::post('/new', 'store_new_blog');
        Route::get('/edit/{id}', 'edit_blog')->name('admin.blog.edit');
        Route::post('/update/{id}', 'update_blog')->name('admin.blog.update');
        Route::post('/clone', 'clone_blog')->name('admin.blog.clone');
        Route::post('/delete/all/lang/{id}', 'delete_blog_all_lang')->name('admin.blog.delete.all.lang');
        Route::post('/bulk-action', 'bulk_action_blog')->name('admin.blog.bulk.action');

        //Blog Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.blog.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.blog.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.blog.comments.bulk.action');

        // Page Settings
        Route::get('/settings', 'blog_settings')->name('admin.blog.settings');
        Route::post('/settings', 'update_blog_settings');
    });


    //BACKEND BLOG CATEGORY AREA
    Route::controller(\Modules\Blog\Http\Controllers\Tenant\Admin\BlogCategoryController::class)->prefix('blog-category')->name('landlord.')->middleware(['adminglobalVariable', 'auth:admin'])->group(function () {
        Route::get('/', 'index')->name('admin.blog.category');
        Route::post('/store', 'new_category')->name('admin.blog.category.store');
        Route::post('/update', 'update_category')->name('admin.blog.category.update');
        Route::post('/delete/all/lang/{id}', 'delete_category_all_lang')->name('admin.blog.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.blog.category.bulk.action');
    });


    /* ------------------------------------------
     COUNTRY MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(CountryManageController::class)->name('landlord.admin.country.')->prefix('country-manage')->group(function () {
        Route::get('/all', 'index')->name('all');
        Route::get('demo-insert', 'demo_all_country_insert')->name('demo.all.country.insert');
        Route::post('new', 'store')->name('new');
        Route::post('update', 'update')->name('update');
        Route::post('delete/{item}', 'destroy')->name('delete');
        Route::post('bulk-action', 'bulk_action')->name('bulk.action');
    });

    /* ------------------------------------------
       TENANT WEBSITE ISSUES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(TenantExceptionController::class)->name('landlord.')->prefix('website-issues')->group(function () {
        Route::get('/', 'website_issues')->name('admin.tenant.website.issues');
        Route::post('/domain-generate', 'generate_domain')->name('admin.failed.domain.generate');
        Route::post('/issue-delete/{id}', 'issue_delete')->name('admin.issue.delete');
        Route::post('/set-database-manually', 'set_database_manually')->name('admin.failed.domain.generate.manually');

        //Website list
        Route::get('/al-website-list', 'all_website_list')->name('admin.tenant.website.list');
        Route::post('/website-delete/{id}', 'delete_website')->name('admin.tenant.website.delete');
    });

    /* ------------------------------------------
     THEMES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(ThemeManageController::class)->name('landlord.')->prefix('theme')->group(function () {
        Route::get('/', 'all_theme')->name('admin.theme');
        Route::post('/update', 'update_status')->name('admin.theme.status.update');
        Route::post('/theme/update', 'update_theme')->name('admin.theme.update');
        Route::get('/settings', 'theme_settings')->name('admin.theme.settings');
        Route::get('/add-new-theme', 'add_new_theme')->name('admin.add.theme');
        Route::post('/add-new-theme', 'store_theme');
        Route::post('/settings', 'theme_settings_update');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
| BACKEND NEWSLETTER AREA
|---------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\NewsletterController::class)->name('landlord.')->prefix('newsletter')->group(function () {
        Route::get('/', 'index')->name('admin.newsletter');
        Route::post('/delete/{id}', 'delete')->name('admin.newsletter.delete');
        Route::post('/single', 'send_mail')->name('admin.newsletter.single.mail');
        Route::get('/all', 'send_mail_all_index')->name('admin.newsletter.mail');
        Route::post('/all', 'send_mail_all');
        Route::post('/new', 'add_new_sub')->name('admin.newsletter.new.add');
        Route::post('/bulk-action', 'bulk_action')->name('admin.newsletter.bulk.action');
        Route::post('/newsletter/verify-mail-send', 'verify_mail_send')->name('admin.newsletter.verify.mail.send');
    });

    /* ------------------------------------------
     PRICE PLAN MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(PricePlanController::class)->name('landlord.')->prefix('price-plan')->group(function () {
        Route::get('/', 'all_price_plan')->name('admin.price.plan');
        Route::get('/new', 'create_price_plan')->name('admin.price.plan.create');
        Route::post('/new', 'store_new_price_plan');
        Route::get('/edit/{id}', 'edit_price_plan')->name('admin.price.plan.edit');
        Route::get('/page-builder/{id}', 'price_plan_builder')->name('admin.price.plan.builder');
        Route::post('/update', 'update')->name('admin.price.plan.update');
        Route::post('/delete/{id}', 'delete')->name('admin.price.plan.delete');
        Route::get('/settings', 'price_plan_settings')->name('admin.price.plan.settings');
        Route::post('/settings', 'update_price_plan_settings');
    });


    /*==============================================
       SUPPORT TICKET MODULE
    ==============================================*/
    Route::controller(SupportTicketController::class)->name('landlord.')->prefix('support-ticket')->group(function () {
        Route::get('/', 'all_tickets')->name('admin.support.ticket.all');
        Route::get('/new', 'new_ticket')->name('admin.support.ticket.new');
        Route::post('/new', 'store_ticket');
        Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.delete');
        Route::get('/view/{id}', 'view')->name('admin.support.ticket.view');
        Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.bulk.action');
        Route::post('/priority-change', 'priority_change')->name('admin.support.ticket.priority.change');
        Route::post('/status-change', 'status_change')->name('admin.support.ticket.status.change');
        Route::post('/send message', 'send_message')->name('admin.support.ticket.send.message');

        /*-----------------------------------
            SUPPORT TICKET : PAGE SETTINGS ROUTES
        ------------------------------------*/
        Route::get('/page-settings', 'page_settings')->name('admin.support.ticket.page.settings');
        Route::post('/page-settings', 'update_page_settings');
    });

    /*-----------------------------------
        SUPPORT TICKET : DEPARTMENT ROUTES
    ------------------------------------*/
    Route::controller(SupportDepartmentController::class)->name('landlord.')->prefix('support-department')->group(function () {
        Route::get('/', 'category')->name('admin.support.ticket.department');
        Route::post('/', 'new_category');
        Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.department.delete');
        Route::post('/update', 'update')->name('admin.support.ticket.department.update');
        Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.department.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
| TESTIMONIAL ROUTES
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\TestimonialController::class)->name('landlord.')->prefix('testimonial')->group(function () {
        Route::get('/all', 'index')->name('admin.testimonial');
        Route::post('/all', 'store');
        Route::post('/clone', 'clone')->name('admin.testimonial.clone');
        Route::post('/update', 'update')->name('admin.testimonial.update');
        Route::post('/delete/{id}', 'delete')->name('admin.testimonial.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.testimonial.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
 | BRAND AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(BrandController::class)->name('landlord.')->prefix('brands')->group(function () {
        Route::get('/', 'index')->name('admin.brands');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.brands.update');
        Route::post('/delete/{id}', 'delete')->name('admin.brands.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.brands.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
 | COUPON AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(CouponManageController::class)->name('landlord.')->prefix('coupons')->group(function () {
        Route::get('/', 'index')->name('admin.coupons');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.coupons.update');
        Route::post('/delete/{id}', 'delete')->name('admin.coupons.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.coupons.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
 | NOTIFICATION MESSAGE AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(NotificationController::class)->name('landlord.')->prefix('notification')->group(function () {
        Route::get('/', 'index')->name('admin.notification.all');
        Route::get('/view/{id}', 'view')->name('admin.notification.view');
        Route::post('/delete/{id}', 'delete')->name('admin.notification.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.notification.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
 | CONTACT MESSAGE AREA ROUTES
 |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(ContactMessageController::class)->name('landlord.')->prefix('contact-message')->group(function () {
        Route::get('/', 'index')->name('admin.contact.message.all');
        Route::get('/view/{id}', 'view')->name('admin.contact.message.view');
        Route::post('/delete/{id}', 'delete')->name('admin.contact.message.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.contact.message.bulk.action');
    });

    /* ------------------------------------------
    WIDGET BUILDER ROUTES
-------------------------------------------- */
    Route::controller(WidgetsController::class)->name('landlord.')->prefix('landlord')->group(function () {
        Route::get('/widgets', 'index')->name('admin.widgets');
        Route::post('/widgets/create', 'new_widget')->name('admin.widgets.new');
        Route::post('/widgets/markup', 'widget_markup')->name('admin.widgets.markup');
        Route::post('/widgets/update', 'update_widget')->name('admin.widgets.update');
        Route::post('/widgets/update/order', 'update_order_widget')->name('admin.widgets.update.order');
        Route::post('/widgets/delete', 'delete_widget')->name('admin.widgets.delete');
    });


    /*==============================================
           FORM BUILDER ROUTES
    ==============================================*/
    Route::controller(CustomFormBuilderController::class)->name('landlord.')->prefix('custom-form-builder')->group(function () {
        /*-------------------------
    CUSTOM FORM BUILDER
--------------------------*/
        Route::get('/all', 'all')->name('admin.form.builder.all');
        Route::post('/new', 'store')->name('admin.form.builder.store');
        Route::get('/edit/{id}', 'edit')->name('admin.form.builder.edit');
        Route::post('/update', 'update')->name('admin.form.builder.update');
        Route::post('/delete/{id}', 'delete')->name('admin.form.builder.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.form.builder.bulk.action');
    });
    /*-------------------------
  CONTACT FORM ROUTES
  --------------------------*/
    Route::controller(FormBuilderController::class)->name('landlord.')->prefix('form-builder')->group(function () {
        Route::get('/contact-form', 'contact_form_index')->name('admin.form.builder.contact');
        Route::post('/contact-form', 'update_contact_form');
    });

    /* Topbar Settings */
    Route::controller(LandlordAdminController::class)->name('landlord.')->prefix('topbar')->group(function () {
        Route::get('/settings', 'topbar_settings')->name('admin.topbar.settings');
        Route::post('/settings', 'update_topbar_settings');
        Route::post('/chart', 'get_chart_data_month')->name('admin.home.chart.data.month');
        Route::post('/chart/day', 'get_chart_by_date_data')->name('admin.home.chart.data.by.day');
    });


    /* Login-Register Settings */
    Route::controller(LandlordAdminController::class)->name('landlord.')->prefix('login-register')->group(function () {
        Route::get('/settings', 'login_register_settings')->name('admin.login.register.settings');
        Route::post('/settings', 'upadate_login_register_settings');
    });

    Route::controller(MenuController::class)->prefix('landlord')->group(function () {
        //MENU MANAGE
        Route::get('/menu', 'index')->name('landlord.admin.menu');
        Route::post('/new-menu', 'store_new_menu')->name('landlord.admin.menu.new');
        Route::get('/menu-edit/{id}', 'edit_menu')->name('landlord.admin.menu.edit');
        Route::post('/menu-update/{id}', 'update_menu')->name('landlord.admin.menu.update');
        Route::post('/menu-delete/{id}', 'delete_menu')->name('landlord.admin.menu.delete');
        Route::post('/menu-default/{id}', 'set_default_menu')->name('landlord.admin.menu.default');
        Route::post('/mega-menu', 'mega_menu_item_select_markup')->name('landlord.admin.mega.menu.item.select.markup');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | ADMIN USER ROLE MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(AdminRoleManageController::class)->name('landlord.')->prefix('admin')->group(function () {
        Route::get('/all', 'all_user')->name('admin.all.user');
        Route::get('/new', 'new_user')->name('admin.new.user');
        Route::post('/new', 'new_user_add');
        Route::get('/user-edit/{id}', 'user_edit')->name('admin.user.edit');
        Route::post('/user-update', 'user_update')->name('admin.user.update');
        Route::post('/user-password-change', 'user_password_change')->name('admin.user.password.change');
        Route::post('/delete-user/{id}', 'new_user_delete')->name('admin.delete.user');

        /*----------------------------
         ALL ADMIN ROLE ROUTES
        -----------------------------*/
        Route::get('/role', 'all_admin_role')->name('admin.all.admin.role');
        Route::get('/role/new', 'new_admin_role_index')->name('admin.role.new');
        Route::post('/role/new', 'store_new_admin_role');
        Route::get('/role/edit/{id}', 'edit_admin_role')->name('admin.user.role.edit');
        Route::post('/role/update', 'update_admin_role')->name('admin.user.role.update');
        Route::post('/role/delete/{id}', 'delete_admin_role')->name('admin.user.role.delete');
    })->middleware("role:Super Admin");;


    /* ------------------------------------------
      TENANT MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(TenantManageController::class)->name('landlord.')->prefix('tenant')->group(function () {
        Route::get('/', 'all_tenants')->name('admin.tenant');
        Route::get('/new', 'new_tenant')->name('admin.tenant.new');
        Route::post('/new', 'new_tenant_store');
        Route::get('/edit-profile/{id}', 'edit_profile')->name('admin.tenant.edit.profile');
        Route::post('/update-profile', 'update_edit_profile')->name('admin.tenant.update.profile');
        Route::post('/delete/{id}', 'delete')->name('admin.tenant.delete');
        Route::post('/change-password', 'update_change_password')->name('admin.tenant.change.password');
        Route::get('/view/{id}', 'view_profile')->name('admin.tenant.view');
        Route::post('/send-mail', 'send_mail')->name('admin.tenant.send.mail');
        Route::post('/resend-verify-mail', 'resend_verify_mail')->name('admin.tenant.resend.verify.mail');
        Route::post('/generate-token', 'generate_api_token')->name('admin.tenant.generate.api.token');
        Route::get('/activity-log', 'tenant_activity_log')->name('admin.tenant.activity.log');
        Route::post('/activity-log-delete/{id}', 'tenant_activity_log_delete')->name('admin.tenant.activity.log.delete');
        Route::get('/activity-log-all-delete', 'tenant_activity_log_all_delete')->name('admin.tenant.activity.log.all.delete');
        Route::get('/details/{id}', 'tenant_details')->name('admin.tenant.details');
        Route::post('/domain/delete/{tenant_id}', 'tenant_domain_delete')->name('admin.tenant.domain.delete');
        Route::post('/tenant/status', 'tenant_account_status')->name('admin.tenant.account.status');
        Route::post('/assign-subscription', 'assign_subscription')->name('admin.tenant.assign.subscription');
        Route::get('/account-settings', 'account_settings')->name('admin.tenant.settings');
        Route::post('/account-settings', 'account_settings_update');
        Route::post('/email-verify-status', 'email_verify_status_update')->name('user.email.verify.enable.status');
        Route::get('/get-theme-via-ajax', 'get_theme_via_ajax')->name('admin.get.theme.via.ajax');

        //Cronjob log
        Route::get('/cronjob-log', 'tenant_cronjob_log')->name('admin.tenant.cronjob.log');
        Route::post('/cronjob-log-delete/{id}', 'tenant_cronjob_log_delete')->name('admin.tenant.cronjob.log.delete');
        Route::get('/cronjob-log-all-delete', 'tenant_cronjob_log_all_delete')->name('admin.tenant.cronjob.log.all.delete');

        //Website instruction Status
        Route::post('/website-instruction-status', 'update_website_instruction_status')->name('admin.tenant.website.instruction.status');
    });

    /* ------------------------------------------
  TENANT WEBSITE INSTRUCTION
-------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\TenantInstructionController::class)->name('landlord.')->prefix('website-instruction')->group(function () {
        Route::get('/all-instruction', 'all_instruction')->name('admin.tenant.website.instruction.all');
        Route::get('/create-instruction', 'create_instruction')->name('admin.tenant.website.instruction.create');
        Route::post('/store-instruction', 'store_instruction')->name('admin.tenant.website.instruction.store');
        Route::get('/edit-instruction/{id}', 'edit_instruction')->name('admin.tenant.website.instruction.edit');
        Route::post('/update-instruction/{id}', 'update_instruction')->name('admin.tenant.website.instruction.update');
        Route::post('/delete/{id}', 'delete_instruction')->name('admin.tenant.website.instruction.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.tenant.website.instruction.bulk.action');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
| PACKAGE ORDER MANAGE
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(OrderManageController::class)->name('landlord.')->prefix('order-manage')->group(function () {
        Route::get('/all', 'all_orders')->name('admin.package.order.manage.all');
        Route::get('/view/{tenant}', 'view_order')->name('admin.package.order.manage.view');
        Route::get('/pending', 'pending_orders')->name('admin.package.order.manage.pending');
        Route::get('/completed', 'completed_orders')->name('admin.package.order.manage.completed');
        Route::get('/in-progress', 'in_progress_orders')->name('admin.package.order.manage.in.progress');
        Route::post('/change-status', 'change_status')->name('admin.package.order.manage.change.status');
        Route::post('/send-mail', 'send_mail')->name('admin.package.order.manage.send.mail');
        Route::post('/delete/{id}', 'order_delete')->name('admin.package.order.manage.delete');
        //thank you page
        Route::get('/success-page', 'order_success_payment')->name('admin.package.order.success.page');
        Route::post('/success-page', 'update_order_success_payment');
        //cancel page
        Route::get('/cancel-page', 'order_cancel_payment')->name('admin.package.order.cancel.page');
        Route::post('/cancel-page', 'update_order_cancel_payment');
        Route::get('/order-page', 'index')->name('admin.package.order.page');
        Route::post('/order-page', 'udpate');
        Route::post('/bulk-action', 'bulk_action')->name('admin.package.order.bulk.action');
        Route::post('/reminder', 'order_reminder')->name('admin.package.order.reminder');
        Route::get('/order-report', 'order_report')->name('admin.package.order.report');
        //payment log route
        Route::post('/generate-invoice/frontend-user', 'generate_package_invoice')->name('package.invoice.generate');
        Route::get('/payment-logs', 'all_payment_logs')->name('admin.payment.logs');
        Route::post('/payment-logs/delete/{id}', 'payment_logs_delete')->name('admin.payment.delete');
        Route::post('/payment-logs/approve/{id}', 'payment_logs_approve')->name('admin.payment.approve');
        Route::post('/payment-logs/bulk-action', 'payment_log_bulk_action')->name('admin.payment.bulk.action');
        Route::get('/payment-logs/report', 'payment_report')->name('admin.payment.report');
        Route::get('/payment-logs/payment-status/{id}', 'payment_log_payment_status_change')->name('admin.package.order.payment.status.change');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
| CUSTOM DOMAIN MANAGE
|----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(CustomDomainController::class)->name('landlord.')->prefix('custom-domain')->group(function () {
        Route::get('/all-pending-requests', 'all_pending_custom_domain_requests')->name('admin.custom.domain.requests.all.pending');
        Route::get('/all-requests', 'all_domain_requests')->name('admin.custom.domain.requests.all');
        Route::get('/settings', 'settings')->name('admin.custom.domain.requests.settings');
        Route::post('/settings', 'update_settings');
        Route::post('/status-change', 'status_change')->name('admin.custom.domain.status.change');
        Route::post('/update', 'update_custom_domain')->name('admin.custom.domain.update');
        Route::post('/delete/{id}', 'delete_request')->name('admin.custom.domain.request.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.custom.domain.bulk.action');
    });

    /* ------------------------------------------
        GENERAL SETTINGS ROUTES
     -------------------------------------------- */
    Route::controller(GeneralSettingsController::class)->prefix('general-settings')->group(function () {

        /* Basic settings */
        Route::get('/basic-settings', 'basic_settings')->name('landlord.admin.general.basic.settings');
        Route::post('/basic-settings', 'update_basic_settings');
        /* Page Settings */

        Route::get('/page-settings', 'page_settings')->name('landlord.admin.general.page.settings');
        Route::post('/page-settings', 'update_page_settings');

        /* site application Settings */
        Route::get('/application-settings', 'application_settings')->name('landlord.admin.general.application.settings');
        Route::post('/application-settings', 'update_application_settings');

        /* site identity Settings */
        Route::get('/site-identity', 'site_identity')->name('landlord.admin.general.site.identity');
        Route::post('/site-identity', 'update_site_identity');

        /* Color Settings */
        Route::get('/color-settings', 'color_settings')->name('landlord.admin.general.color.settings');
        Route::post('/color-settings', 'update_color_settings');

        /* Typography Settings */
        Route::get('/typography-settings', 'typography_settings')->name('landlord.admin.general.typography.settings');
        Route::post('/typography-settings', 'update_typography_settings');
        Route::post('typography-settings/single', 'GeneralSettingsController@get_single_font_variant')->name('landlord.admin.general.typography.single');

        /* Custom forn Settings */
        Route::post('/add-custom-font', 'add_custom_font')->name('landlord.admin.add.custom.font');
        Route::post('/set-custom-font', 'set_custom_font')->name('landlord.admin.set.custom.font');
        Route::get('/delete-custom-font/{font}', 'delete_custom_font')->name('landlord.admin.custom.font.delete');

        /* SEO Settings */
        Route::get('/seo-settings', 'seo_settings')->name('landlord.admin.general.seo.settings');
        Route::post('/seo-settings', 'update_seo_settings');

        //        /* Payment Settings (Static) */
        //        Route::get('/payment-settings','payment_settings')->name('landlord.admin.general.payment.settings');
        //        Route::post('/payment-settings','update_payment_settings');

        /* Third party scripts Settings */
        Route::get('/third-party-script-settings', 'third_party_script_settings')->name('landlord.admin.general.third.party.script.settings');
        Route::post('/third-party-script-settings', 'update_third_party_script_settings');

        /* smtp Settings */
        Route::get('/smtp-settings', 'smtp_settings')->name('landlord.admin.general.smtp.settings');
        Route::post('/smtp-settings', 'update_smtp_settings');
        Route::post('/send-test-mail', 'send_test_mail')->name('landlord.admin.general.smtp.settings.test.mail');

        /* custom css Settings */
        Route::get('/custom-css-settings', 'custom_css_settings')->name('landlord.admin.general.custom.css.settings');
        Route::post('/custom-css-settings', 'update_custom_css_settings');

        /* js css Settings */
        Route::get('/custom-js-settings', 'custom_js_settings')->name('landlord.admin.general.custom.js.settings');
        Route::post('/custom-js-settings', 'update_custom_js_settings');

        /* Database Upgrade Settings */
        Route::get('/database-upgrade-settings', 'database_upgrade')->name('landlord.admin.general.database.upgrade.settings');
        Route::post('/database-upgrade-settings', 'update_database_upgrade');

        /* Cache  Settings */
        Route::get('/cache-settings', 'cache_settings')->name('landlord.admin.general.cache.settings');
        Route::post('/cache-settings', 'update_cache_settings');

        //GDPR Settings
        Route::get('/gdpr-settings', 'gdpr_settings')->name('landlord.admin.general.gdpr.settings');
        Route::post('/gdpr-settings', 'update_gdpr_cookie_settings');

        /* License Upgrade Settings */
        Route::get('/license-settings', 'license_settings')->name('landlord.admin.general.license.settings');
        Route::post('/license-settings', 'update_license_settings');


        Route::post('/license-setting-verify', 'license_key_generate')->name('landlord.admin.general.license.key.generate');
        Route::get('/update-check', 'update_version_check')->name('landlord.admin.general.update.version.check');
        Route::post('/download-update/{productId}/{tenant}', 'updateDownloadLatestVersion')->name('landlord.admin.general.update.download.settings');
        Route::get('/software-update-setting', 'software_update_check_settings')->name('landlord.admin.general.software.update.settings');


        /* Sitemap Settings */
        Route::get('/sitemap-settings', 'sitemap_settings')->name('landlord.admin.general.sitemap.settings');
        Route::post('/sitemap-settings', 'update_sitemap_settings');
        Route::post('/sitemap-settings/delete', 'delete_sitemap_settings')->name('landlord.admin.general.sitemap.settings.delete');
    });


    /* ------------------------------------------
    PAYMENT SETTINGS ROUTES
 -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PaymentSettingsController::class)->prefix('payment-settings')->group(function () {

        //Currencies
        Route::get('/currency', 'currency_settings')->name('landlord.admin.payment.currency.settings');
        Route::post('/currency', 'update_currency_settings');

        Route::get('/paypal', 'paypal_settings')->name('landlord.admin.payment.paypal.settings');
        Route::get('/paytm', 'paytm_settings')->name('landlord.admin.payment.paytm.settings');
        Route::get('/stripe', 'stripe_settings')->name('landlord.admin.payment.stripe.settings');
        Route::get('/razorpay', 'razorpay_settings')->name('landlord.admin.payment.razorpay.settings');
        Route::get('/paystack', 'paystack_settings')->name('landlord.admin.payment.paystack.settings');
        Route::get('/mollie', 'mollie_settings')->name('landlord.admin.payment.mollie.settings');
        Route::get('/payfast', 'payfast_settings')->name('landlord.admin.payment.payfast.settings');
        Route::get('/midtrans', 'midtrans_settings')->name('landlord.admin.payment.midtrans.settings');
        Route::get('/cashfree', 'cashfree_settings')->name('landlord.admin.payment.cashfree.settings');
        Route::get('/instamojo', 'instamojo_settings')->name('landlord.admin.payment.instamojo.settings');
        Route::get('/marcadopago', 'marcadopago_settings')->name('landlord.admin.payment.marcadopago.settings');
        Route::get('/zitopay', 'zitopay_settings')->name('landlord.admin.payment.zitopay.settings');
        Route::get('/squareup', 'squareup_settings')->name('landlord.admin.payment.squareup.settings');
        Route::get('/cinetpay', 'cinetpay_settings')->name('landlord.admin.payment.cinetpay.settings');
        Route::get('/paytabs', 'paytabs_settings')->name('landlord.admin.payment.paytabs.settings');
        Route::get('/billplz', 'billplz_settings')->name('landlord.admin.payment.billplz.settings');
        Route::get('/bank-transfer', 'bank_transfer_settings')->name('landlord.admin.payment.bank_transfer.settings');
        Route::get('/manual_payment', 'manual_payment_settings')->name('landlord.admin.payment.manual_payment.settings');
        Route::get('/flutterwave', 'flutterwave_settings')->name('landlord.admin.payment.flutterwave.settings');
        Route::get('/toyyibpay', 'toyyibpay_settings')->name('landlord.admin.payment.toyyibpay.settings');
        Route::get('/pagali', 'pagali_settings')->name('landlord.admin.payment.pagali.settings');
        Route::get('/authorizenet', 'authorizenet_settings')->name('landlord.admin.payment.authorizenet.settings');
        Route::get('/sitesway', 'sitesway_settings')->name('landlord.admin.payment.sitesway.settings');
        Route::get('/kinetic', 'kinetic_settings')->name('landlord.admin.payment.kinetic.settings');
        Route::get('/chargilypay', 'chargilypay_settings')->name('landlord.admin.payment.chargilypay.settings');

        //update
        Route::post('/gateway-update', 'update_gateway_settings')->name('landlord.admin.payment.gateway.update');
    });

    /* ------------------------------------------
        LANGUAGES
    -------------------------------------------- */
    Route::controller(LanguagesController::class)->name('landlord.')->prefix('languages')->group(function () {
        Route::get('/', 'index')->name('admin.languages');
        Route::get('/languages/words/all/{id}', 'all_edit_words')->name('admin.languages.words.all');
        Route::post('/languages/words/update/{id}', 'update_words')->name('admin.languages.words.update');
        Route::post('/languages/new', 'store')->name('admin.languages.new');
        Route::post('/languages/update', 'update')->name('admin.languages.update');
        Route::post('/languages/delete/{id}', 'delete')->name('admin.languages.delete');
        Route::post('/languages/default/{id}', 'make_default')->name('admin.languages.default');
        Route::post('/languages/clone', 'clone_languages')->name('admin.languages.clone');
        Route::post('/add-new-string', 'add_new_string')->name('admin.languages.add.string');
        Route::post('/languages/regenerate-source-text', 'regenerate_source_text')->name('admin.languages.regenerate.source.texts');
    });

    /* ------------------------------------------
      MEDIA UPLOADER ROUTES
     -------------------------------------------- */
    Route::prefix('media-upload')->controller(MediaUploaderController::class)->group(function () {
        Route::post('/delete', 'delete_upload_media_file')->name('landlord.admin.upload.media.file.delete');
        Route::get('/page', 'all_upload_media_images_for_page')->name('landlord.admin.upload.media.images.page');
        Route::post('/alt', 'alt_change_upload_media_file')->name('landlord.admin.upload.media.file.alt.change');
    });

    //Others Page Settings
    Route::prefix('error')->controller(Error404PageManage::class)->group(function () {
        Route::get('/404-page-manage', 'error_404_page_settings')->name('landlord.admin.404.page.settings');
        Route::post('/404-page-manage', 'update_error_404_page_settings');
    });

    Route::prefix('maintenance')->controller(MaintainsPageController::class)->group(function () {
        Route::get('/settings', 'maintains_page_settings')->name('landlord.admin.maintains.page.settings');
        Route::post('/settings', 'update_maintains_page_settings');
    });



    //Seeder Data settings
    Route::prefix('demo-data')->controller(SeederSettingsController::class)->group(function () {
        //Donation
        Route::get('/donation-settings', 'donation_index')->name('landlord.admin.seeder.donation.index');
        Route::get('/donation-category-data-settings', 'donation_category_data_settings')->name('landlord.admin.seeder.donation.category.data.settings');
        Route::post('/donation-category-data-settings', 'update_donation_category_data_settings');
        Route::get('/donation-data-settings', 'donation_data_settings')->name('landlord.admin.seeder.donation.data.settings');
        Route::post('/donation-data-settings', 'update_donation_data_settings');

        //Donation Activities
        Route::get('/donation-activities-category-data-settings', 'donation_activities_category_data_settings')->name('landlord.admin.seeder.donation.activities.category.data.settings');
        Route::post('/donation-activities-category-data-settings', 'update_activities_donation_category_data_settings');
        Route::get('/donation-activities-data-settings', 'donation_activities_data_settings')->name('landlord.admin.seeder.donation.activities.data.settings');
        Route::post('/donation-activities-data-settings', 'update_activities_donation_data_settings');

        //Appointment
        Route::get('/appointment-settings', 'appointment_index')->name('landlord.admin.seeder.appointment.index');
        Route::get('/appointment-category-data-settings', 'appointment_category_data_settings')->name('landlord.admin.seeder.appointment.category.data.settings');
        Route::post('/appointment-category-data-settings', 'update_appointment_category_data_settings');
        Route::get('/appointment-sub-category-data-settings', 'appointment_sub_category_data_settings')->name('landlord.admin.seeder.appointment.sub.category.data.settings');
        Route::post('/appointment-sub-category-data-settings', 'update_appointment_sub_category_data_settings');

        Route::get('/sub-appointment-data-settings', 'sub_appointment_data_settings')->name('landlord.admin.seeder.sub.appointment.data.settings');
        Route::post('/sub-appointment-data-settings', 'update_sub_appointment_data_settings');
        Route::get('/appointment-data-settings', 'appointment_data_settings')->name('landlord.admin.seeder.appointment.data.settings');
        Route::post('/appointment-data-settings', 'update_appointment_data_settings');

        //Event
        Route::get('/event-settings', 'event_index')->name('landlord.admin.seeder.event.index');
        Route::get('/event-category-data-settings', 'event_category_data_settings')->name('landlord.admin.seeder.event.category.data.settings');
        Route::post('/event-category-data-settings', 'update_event_category_data_settings');
        Route::get('/event-data-settings', 'event_data_settings')->name('landlord.admin.seeder.event.data.settings');
        Route::post('/event-data-settings', 'update_event_data_settings');

        //Job
        Route::get('/job-settings', 'job_index')->name('landlord.admin.seeder.job.index');
        Route::get('/job-category-data-settings', 'job_category_data_settings')->name('landlord.admin.seeder.job.category.data.settings');
        Route::post('/job-category-data-settings', 'update_job_category_data_settings');
        Route::get('/job-data-settings', 'job_data_settings')->name('landlord.admin.seeder.job.data.settings');
        Route::post('/job-data-settings', 'update_job_data_settings');

        //Portfolio
        Route::get('/portfolio-settings', 'portfolio_index')->name('landlord.admin.seeder.portfolio.index');
        Route::get('/portfolio-category-data-settings', 'portfolio_category_data_settings')->name('landlord.admin.seeder.portfolio.category.data.settings');
        Route::post('/portfolio-category-data-settings', 'update_portfolio_category_data_settings');
        Route::get('/portfolio-data-settings', 'portfolio_data_settings')->name('landlord.admin.seeder.portfolio.data.settings');
        Route::post('/portfolio-data-settings', 'update_portfolio_data_settings');

        //Pages
        Route::get('/pages-settings', 'pages_index')->name('landlord.admin.seeder.pages.index');
        Route::get('/pages-data-settings', 'pages_data_settings')->name('landlord.admin.seeder.pages.data.settings');
        Route::post('/pages-data-settings', 'update_pages_data_settings');

        //Blogs
        Route::get('/blog-settings', 'blog_index')->name('landlord.admin.seeder.blog.index');
        Route::get('/blog-category-data-settings', 'blog_category_data_settings')->name('landlord.admin.seeder.blog.category.data.settings');
        Route::post('/blog-category-data-settings', 'update_blog_category_data_settings');
        Route::get('/blog-data-settings', 'blog_data_settings')->name('landlord.admin.seeder.blog.data.settings');
        Route::post('/blog-data-settings', 'update_blog_data_settings');

        //Services
        Route::get('/service-settings', 'service_index')->name('landlord.admin.seeder.service.index');
        Route::get('/service-category-data-settings', 'service_category_data_settings')->name('landlord.admin.seeder.service.category.data.settings');
        Route::post('/service-category-data-settings', 'update_service_category_data_settings');
        Route::get('/service-data-settings', 'service_data_settings')->name('landlord.admin.seeder.service.data.settings');
        Route::post('/service-data-settings', 'update_service_data_settings');

        //Article/Knowledgebase
        Route::get('/article-settings', 'article_index')->name('landlord.admin.seeder.article.index');
        Route::get('/article-category-data-settings', 'article_category_data_settings')->name('landlord.admin.seeder.article.category.data.settings');
        Route::post('/article-category-data-settings', 'update_article_category_data_settings');
        Route::get('/article-data-settings', 'article_data_settings')->name('landlord.admin.seeder.article.data.settings');
        Route::post('/article-data-settings', 'update_article_data_settings');

        //Testimonial
        Route::get('/testimonial-settings', 'testimonial_index')->name('landlord.admin.seeder.testimonial.index');
        Route::get('/testimonial-data-settings', 'testimonial_data_settings')->name('landlord.admin.seeder.testimonial.data.settings');
        Route::post('/testimonial-data-settings', 'update_testimonial_data_settings');

        //FAQ
        Route::get('/faq-settings', 'faq_index')->name('landlord.admin.seeder.faq.index');
        Route::get('/faq-category-data-settings', 'faq_category_data_settings')->name('landlord.admin.seeder.faq.category.data.settings');
        Route::post('/faq-category-data-settings', 'update_faq_category_data_settings');
        Route::get('/faq-data-settings', 'faq_data_settings')->name('landlord.admin.seeder.faq.data.settings');
        Route::post('/faq-data-settings', 'update_faq_data_settings');

        //Wedding or any Price Plan
        Route::get('/price-plan-settings', 'price_plan_index')->name('landlord.admin.seeder.price.plan.index');
        Route::get('/price-plan-data-settings', 'price_plan_data_settings')->name('landlord.admin.seeder.price.plan.data.settings');
        Route::post('/price-plan-data-settings', 'update_price_plan_data_settings');

        //Image Gallery
        Route::get('/gallery-settings', 'gallery_index')->name('landlord.admin.seeder.gallery.index');
        Route::get('/gallery-category-data-settings', 'gallery_category_data_settings')->name('landlord.admin.seeder.gallery.category.data.settings');
        Route::post('/gallery-category-data-settings', 'update_gallery_category_data_settings');
        Route::get('/gallery-data-settings', 'gallery_data_settings')->name('landlord.admin.seeder.gallery.data.settings');
        Route::post('/gallery-data-settings', 'update_gallery_data_settings');

        //====================== PRODUCT SEEDER ==========================
        Route::get('/product-settings', 'product_index')->name('landlord.admin.seeder.product.index');
        //category
        Route::get('/product-category-data-settings', 'product_category_data_settings')->name('landlord.admin.seeder.product.category.data.settings');
        Route::post('/product-category-data-settings', 'update_product_category_data_settings');

        //subcategory
        Route::get('/product-subcategory-data-settings', 'product_subcategory_data_settings')->name('landlord.admin.seeder.product.subcategory.data.settings');
        Route::post('/product-subcategory-data-settings', 'update_product_subcategory_data_settings');

        //child-category
        Route::get('/product-childcategory-data-settings', 'product_childcategory_data_settings')->name('landlord.admin.seeder.product.childcategory.data.settings');
        Route::post('/product-childcategory-data-settings', 'update_product_childcategory_data_settings');

        //colors
        Route::get('/product-color-data-settings', 'product_color_data_settings')->name('landlord.admin.seeder.product.color.data.settings');
        Route::post('/product-color-data-settings', 'update_product_color_data_settings');

        //colors
        Route::get('/product-size-data-settings', 'product_size_data_settings')->name('landlord.admin.seeder.product.size.data.settings');
        Route::post('/product-size-data-settings', 'update_product_size_data_settings');

        //delivery options
        Route::get('/product-delivery-option-data-settings', 'product_delivery_option_data_settings')->name('landlord.admin.seeder.product.delivery.option.data.settings');
        Route::post('/product-delivery-option-data-settings', 'update_product_delivery_option_data_settings');

        //badge
        Route::get('/product-badge-data-settings', 'product_badge_data_settings')->name('landlord.admin.seeder.product.badge.data.settings');
        Route::post('/product-badge-data-settings', 'update_product_badge_data_settings');

        //campaign
        Route::get('/product-campaign-data-settings', 'product_campaign_data_settings')->name('landlord.admin.seeder.product.campaign.data.settings');
        Route::post('/product-campaign-data-settings', 'update_product_campaign_data_settings');

        //return policy
        Route::get('/product-return-policy-data-settings', 'product_return_policy_data_settings')->name('landlord.admin.seeder.product.return.policy.data.settings');
        Route::post('/product-return-policy-data-settings', 'update_product_return_policy_data_settings');

        //products
        Route::get('/product-data-settings', 'product_data_settings')->name('landlord.admin.seeder.product.data.settings');
        Route::post('/product-data-settings', 'update_product_data_settings');
        //====================== PRODUCT SEEDER ==========================

        //language
        Route::get('/language-data-settings', 'language_data_settings')->name('landlord.admin.seeder.language.data.settings');
        Route::post('/language-data-settings', 'update_language_data_settings');
        Route::post('/make-default-language-data-settings/{id}', 'make_default_language_data_settings')->name('landlord.admin.seeder.language.data.settings.make.default');


        //Tenant demo data
        Route::get('/tenant-demo-data-settings', 'tenant_demo_data_settings')->name('landlord.admin.seeder.demo.data.settings');
        Route::post('/language-data-settings', 'update_tenant_demo_data_settings')->name('update.landlord.admin.seeder.demo.data.settings');
        //        Route::post('/make-default-language-data-settings/{id}', 'make_default_language_data_settings')->name('landlord.admin.seeder.language.data.settings.make.default');


        //====================== WIDGET SEEDER SETTINGS ==========================
        //index
        Route::get('/widget-settings', 'widget_index')->name('landlord.admin.seeder.widget.index');

        //blog category
        Route::get('/blog-category-widget-data-settings', 'blog_category_widget_data_settings')->name('landlord.admin.seeder.widget.blog.category.data.settings');
        Route::post('/blog-category-widget-data-settings', 'update_blog_category_widget_data_settings');
        //popular blogs
        Route::get('/popular-blogs-widget-data-settings', 'popular_blog_widget_data_settings')->name('landlord.admin.seeder.widget.popular.blog.data.settings');
        Route::post('/popular-blogs-widget-data-settings', 'update_popular_blog_widget_data_settings');

        //service category
        Route::get('/service-category-widget-data-settings', 'service_category_widget_data_settings')->name('landlord.admin.seeder.widget.service.category.data.settings');
        Route::post('/service-category-widget-data-settings', 'update_service_category_widget_data_settings');
        //popular service
        Route::get('/popular-service-widget-data-settings', 'popular_service_widget_data_settings')->name('landlord.admin.seeder.widget.popular.service.data.settings');
        Route::post('/popular-service-widget-data-settings', 'update_popular_service_widget_data_settings');
        //query submit
        Route::get('/query-submit-widget-data-settings', 'query_submit_widget_data_settings')->name('landlord.admin.seeder.widget.query.submit.data.settings');
        Route::post('/query-submit-widget-data-settings', 'update_query_submit_widget_data_settings');

        //about us
        Route::get('/about-us-widget-data-settings', 'about_us_widget_data_settings')->name('landlord.admin.seeder.widget.about.us.data.settings');
        Route::post('/about-us-widget-data-settings', 'update_about_us_widget_data_settings');

        //navigation menu
        Route::get('/navigation-menu-widget-data-settings', 'navigation_widget_data_settings')->name('landlord.admin.seeder.widget.navigation.menu.data.settings');
        Route::post('/navigation-menu-widget-data-settings', 'update_navigation_widget_data_settings');

        //custom link
        Route::get('/custom-link-widget-data-settings', 'custom_link_widget_data_settings')->name('landlord.admin.seeder.widget.custom.link.data.settings');
        Route::post('/custom-link-widget-data-settings', 'update_custom_link_widget_data_settings');

        //contact info
        Route::get('/contact-info-widget-data-settings', 'contact_info_widget_data_settings')->name('landlord.admin.seeder.widget.contact.info.data.settings');
        Route::post('/contact-info-widget-data-settings', 'update_contact_info_widget_data_settings');

        //donation category
        Route::get('/donation-category-widget-data-settings', 'donation_category_widget_data_settings')->name('landlord.admin.seeder.widget.donation.category.data.settings');
        Route::post('/donation-category-widget-data-settings', 'update_donation_category_widget_data_settings');

        //recent donation
        Route::get('/recent-donation-widget-data-settings', 'recent_donation_widget_data_settings')->name('landlord.admin.seeder.widget.recent.donation.data.settings');
        Route::post('/recent-donation-widget-data-settings', 'update_recent_donation_widget_data_settings');

        //donation activities category
        Route::get('/donation-activities-category-widget-data-settings', 'donation_activity_category_widget_data_settings')->name('landlord.admin.seeder.widget.donation.activity.category.data.settings');
        Route::post('/donation-activities-category-widget-data-settings', 'update_donation_activity_category_widget_data_settings');

        //recent activities
        Route::get('/recent-donation-activities-widget-data-settings', 'recent_donation_activities_widget_data_settings')->name('landlord.admin.seeder.widget.recent.donation.activities.data.settings');
        Route::post('/recent-donation-activities-widget-data-settings', 'update_recent_donation_activities_widget_data_settings');

        //footer recent events
        Route::get('/footer-recent-event-widget-data-settings', 'footer_recent_event_widget_data_settings')->name('landlord.admin.seeder.widget.footer.recent.event.data.settings');
        Route::post('/footer-recent-event-widget-data-settings', 'update_footer_recent_event_widget_data_settings');
        //event category
        Route::get('/event-category-widget-data-settings', 'event_category_widget_data_settings')->name('landlord.admin.seeder.widget.event.category.data.settings');
        Route::post('/event-category-widget-data-settings', 'update_event_category_widget_data_settings');
        //sidebar recent events
        Route::get('/sidebar-recent-event-widget-data-settings', 'sidebar_recent_event_widget_data_settings')->name('landlord.admin.seeder.widget.sidebar.recent.event.data.settings');
        Route::post('/sidebar-recent-event-widget-data-settings', 'update_sidebar_recent_event_widget_data_settings');

        //job category
        Route::get('/job-category-widget-data-settings', 'job_category_widget_data_settings')->name('landlord.admin.seeder.widget.job.category.data.settings');
        Route::post('/job-category-widget-data-settings', 'update_job_category_widget_data_settings');
        //recent jobs
        Route::get('/job-event-widget-data-settings', 'recent_job_widget_data_settings')->name('landlord.admin.seeder.widget.sidebar.recent.job.data.settings');
        Route::post('/job-event-widget-data-settings', 'update_recent_job_widget_data_settings');

        //article newsletter
        Route::get('/article-newsletter-widget-data-settings', 'article_newsletter_widget_data_settings')->name('landlord.admin.seeder.widget.sidebar.article.newsletter.data.settings');
        Route::post('/article-newsletter-widget-data-settings', 'update_article_newsletter_widget_data_settings');
        //article category
        Route::get('/article-category-widget-data-settings', 'article_category_widget_data_settings')->name('landlord.admin.seeder.widget.article.category.data.settings');
        Route::post('/article-category-widget-data-settings', 'update_article_category_widget_data_settings');
        //recent articles
        Route::get('/recent-article-widget-data-settings', 'recent_article_widget_data_settings')->name('landlord.admin.seeder.widget.recent.article.data.settings');
        Route::post('/recent-article-widget-data-settings', 'update_recent_article_widget_data_settings');
        //====================== WIDGET SEEDER SETTINGS ==========================


        //====================== PAGE BUILDER SEEDER SETTINGS ==========================


        Route::group(['prefix' => 'page-builder', 'as' => 'landlord.'], function () {
            Route::get('/settings', 'page_builder_index')->name('admin.seeder.page.builder.index');

            //========  Donation Home =========

            //Header Area
            Route::get('/donation-home-header-data', 'donation_home_page_header_data_settings')->name('admin.seeder.donation.home.page.header.data.settings');
            Route::post('/donation-home-header-data', 'update_donation_home_page_header_data_settings');
            //Brand Area
            Route::get('/donation-home-brand-data', 'donation_home_page_brand_data_settings')->name('admin.seeder.donation.home.page.brand.data.settings');
            Route::post('/donation-home-brand-data', 'update_donation_home_page_brand_data_settings');
            //Campaign Area
            Route::get('/donation-home-campaign-data', 'donation_home_page_campaign_data_settings')->name('admin.seeder.donation.home.page.campaign.data.settings');
            Route::post('/donation-home-campaign-data', 'update_donation_home_page_campaign_data_settings');
            //About Area
            Route::get('/donation-home-about-data', 'donation_home_page_about_data_settings')->name('admin.seeder.donation.home.page.about.data.settings');
            Route::post('/donation-home-about-data', 'update_donation_home_page_about_data_settings');

            //Campaign Area Two
            Route::get('/donation-home-campaign-two-data', 'donation_home_page_campaign_two_data_settings')->name('admin.seeder.donation.home.page.campaign.two.data.settings');
            Route::post('/donation-home-campaign-two-data', 'update_donation_home_page_campaign_two_data_settings');
            //Activities Area
            Route::get('/donation-home-activities-data', 'donation_home_page_activities_data_settings')->name('admin.seeder.donation.home.page.activities.data.settings');
            Route::post('/donation-home-activities-data', 'update_donation_home_page_activities_data_settings');
            //Testimonial Area
            Route::get('/donation-home-testimonial-data', 'donation_home_page_testimonial_data_settings')->name('admin.seeder.donation.home.page.testimonial.data.settings');
            Route::post('/donation-home-testimonial-data', 'update_donation_home_page_testimonial_data_settings');
            //Blog Area
            Route::get('/donation-home-blog-data', 'donation_home_page_blog_data_settings')->name('admin.seeder.donation.home.page.blog.data.settings');
            Route::post('/donation-home-blog-data', 'update_donation_home_page_blog_data_settings');

            //========  Donation Home =========
        });



        //====================== PAGE BUILDER SEEDER SETTINGS ==========================





    });


    /*--------------------------
      PAGE BUILDER
    --------------------------*/
    Route::controller(PageBuilderController::class)->group(function () {
        Route::post('/update', 'update_addon_content')->name('landlord.admin.page.builder.update');
        Route::post('/new', 'store_new_addon_content')->name('landlord.admin.page.builder.new');
        Route::post('/delete', 'delete')->name('landlord.admin.page.builder.delete');
        Route::post('/update-order', 'update_addon_order')->name('landlord.admin.page.builder.update.addon.order');
        Route::post('/get-admin-markup', 'get_admin_panel_addon_markup')->name('landlord.admin.page.builder.get.addon.markup');
    });
});

/* ------------------------------------------
      MEDIA UPLOADER ROUTES
-------------------------------------------- */
Route::prefix('media-upload')->controller(MediaUploaderController::class)->group(function () {
    Route::post('/media-upload/all', 'all_upload_media_file')->name('landlord.admin.upload.media.file.all');
    Route::post('/media-upload', 'upload_media_file')->name('landlord.admin.upload.media.file');
    Route::post('/media-upload/loadmore', 'get_image_for_load_more')->name('landlord.admin.upload.media.file.loadmore');
});
