<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\CountryManage\Http\Controllers\Tenant\Admin\CountryManageController;
use Modules\CountryManage\Http\Controllers\Tenant\Admin\StateController;
use Modules\TaxModule\Http\Controllers\Tenant\Admin\CountryTaxController;
use Modules\TaxModule\Http\Controllers\Tenant\Admin\StateTaxController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    'web',
    \App\Http\Middleware\Tenant\InitializeTenancyByDomainCustomisedMiddleware::class,
    PreventAccessFromCentralDomains::class,
    'auth:admin',
    'tenant_admin_glvar',
    'package_expire',
    'tenantAdminPanelMailVerify',
    'setlang'
])->prefix('admin-home')->name('tenant.')->group(function () {

    Route::get('/', [\App\Http\Controllers\Tenant\Admin\TenantDashboardController::class, 'dashboard'])->name('admin.dashboard');

    /* ------------------------------------------
       ADMIN DASHBOARD ROUTES
   -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\LandlordAdminController::class)->group(function () {
        Route::get('/activity-logs', 'activity_logs')->name('admin.activity.log');
        Route::get('/edit-profile', 'edit_profile')->name('admin.edit.profile');
        Route::get('/change-password', 'change_password')->name('admin.change.password');
        Route::post('/edit-profile', 'update_edit_profile');
        Route::post('/change-password', 'update_change_password');

        Route::get('/change-tes', 'update_change_password')->name('admin.test');
        Route::get('/change-tesy', 'update_change_password')->name('admin.test.test');
    });

    /* ------------------------------------------
        LANGUAGES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\LanguagesController::class)->prefix('languages')->group(function () {
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
     FAQ CATEGORY ROUTES
    /* -------------------------------------------- */
    Route::prefix('faq-category')->controller(\App\Http\Controllers\Tenant\Admin\FaqCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('admin.faq.category');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.faq.category.update');
        Route::post('/delete/{id}', 'delete')->name('admin.faq.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.faq.category.bulk.action');
    });

    /*------------------------------------------
    FAQ ROUTES
    -------------------------------------------- */
    Route::prefix('faq')->controller(\App\Http\Controllers\Tenant\Admin\FaqController::class)->group(function () {
        Route::get('/all', 'index')->name('admin.faq');
        Route::post('/all', 'store');
        Route::post('/update', 'update')->name('admin.faq.update');
        Route::post('/delete/{id}', 'delete')->name('admin.faq.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.faq.bulk.action');
    });


    /* ------------------------------------------
     PAGES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PagesController::class)->prefix('pages')->group(function (){
        Route::get('/','all_pages')->name('admin.pages');
        Route::get('/new','create_page')->name('admin.pages.create');
        Route::post('/new','store_new_page');
        Route::get('/edit/{id}','edit_page')->name('admin.pages.edit');
        Route::get('/page-builder/{id}','page_builder')->name('admin.pages.builder');
        Route::post('/update','update')->name('admin.pages.update');
        Route::post('/delete/{id}','delete')->name('admin.pages.delete');
    });



    /* ------------------------------------------
     IMAGE GALLERY CATEGORY ROUTES
    /* -------------------------------------------- */
    Route::prefix('image-gallery-category')->controller(\App\Http\Controllers\Tenant\Admin\ImageGalleryCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('admin.image.gallery.category');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.image.gallery.category.update');
        Route::post('/delete/{id}', 'delete')->name('admin.image.gallery.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.image.gallery.category.bulk.action');
    });


    /*------------------------------------------
     IMAGE GALLERY ROUTES
    -------------------------------------------- */
    Route::prefix('image-gallery')->controller(\App\Http\Controllers\Tenant\Admin\ImageGalleryController::class)->group(function () {
        Route::get('/all', 'index')->name('admin.image.gallery');
        Route::post('/all', 'store');
        Route::post('/update', 'update')->name('admin.image.gallery.update');
        Route::post('/clone', 'clone')->name('admin.image.gallery.clone');
        Route::post('/delete/{id}', 'delete')->name('admin.image.gallery.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.image.gallery.bulk.action');
    });


   /*------------------------------------------
     WEDDING PRICE PLAN ROUTES
    -------------------------------------------- */
    Route::prefix('wedding-price-plan')->controller(\App\Http\Controllers\Tenant\Admin\WeddingPlanController::class)->group(function () {
        Route::get('/all', 'index')->name('admin.wedding.price.plan');
        Route::post('/all', 'store');
        Route::post('/update', 'update')->name('admin.wedding.price.plan.update');
        Route::post('/delete/{id}', 'delete')->name('admin.wedding.price.plan.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.wedding.price.plan.bulk.action');

        //Payment Data
        Route::get('/payment-logs', 'wedding_payment_logs')->name('admin.wedding.payment.logs');
        Route::post('/invoice/generate', 'wedding_invoice')->name('admin.wedding.invoice.generate');
        Route::post('/payment-log-delete/{id}', 'wedding_payment_log_delete')->name('admin.wedding.payment.log.delete');
        Route::post('/payment-log-bulk-action', 'wedding_payment_log_bulk_action')->name('admin.wedding.payment.log.bulk.action');
        Route::post('/payment/accept/{id}', 'wedding_payment_accept')->name('admin.wedding.payment.accept');
    });

    /* ------------------------------------------
    MEDIA UPLOADER ROUTES
   -------------------------------------------- */
    Route::prefix('media-upload')->controller(\App\Http\Controllers\Landlord\Admin\MediaUploaderController::class)->group(function () {
        Route::post('/delete', 'delete_upload_media_file')->name('admin.upload.media.file.delete');
        Route::get('/page', 'all_upload_media_images_for_page')->name('admin.upload.media.images.page');
        Route::post('/alt', 'alt_change_upload_media_file')->name('admin.upload.media.file.alt.change');
    });

    /* ------------------------------------------
      MEDIA UPLOADER ROUTES
    -------------------------------------------- */
    Route::prefix('media-upload')->controller(\App\Http\Controllers\Landlord\Admin\MediaUploaderController::class)->group(function () {
        Route::post('/media-upload/all', 'all_upload_media_file')->name('admin.upload.media.file.all');
        Route::post('/media-upload', 'upload_media_file')->name('admin.upload.media.file');
        Route::post('/media-upload/loadmore', 'get_image_for_load_more')->name('admin.upload.media.file.loadmore');
    });

    /*--------------------------
      PAGE BUILDER
    --------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\PageBuilderController::class)->group(function () {
        Route::post('/update', 'update_addon_content')->name('admin.page.builder.update');
        Route::post('/new', 'store_new_addon_content')->name('admin.page.builder.new');
        Route::post('/delete', 'delete')->name('admin.page.builder.delete');
        Route::post('/update-order', 'update_addon_order')->name('admin.page.builder.update.addon.order');
        Route::post('/get-admin-markup', 'get_admin_panel_addon_markup')->name('admin.page.builder.get.addon.markup');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | CUSTOM DOMAIN MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\CustomDomainController::class)->prefix('custom-domain')->group(function () {
        Route::get('/custom-domain-request', 'custom_domain_request')->name('admin.custom.domain.requests');
        Route::post('/custom-domain-request', 'custom_domain_request_change');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | ADMIN USER ROLE MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\AdminRoleManageController::class)->prefix('admin')->group(function () {
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
    })->middleware("role:Super Admin");

    /*--------------------------
      TOPBAR SETTING ROUTE
    ----------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\LandlordAdminController::class)->prefix('topbar')->group(function () {
        Route::get('/settings','topbar_settings')->name('admin.topbar.settings');
        Route::post('/settings','update_topbar_settings');
    });

/*--------------------------
  THEME SETTINGS ROUTE
----------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\OtherSettingsController::class)->prefix('theme')->group(function () {
        Route::get('/settings','theme_settings')->name('admin.theme');
        Route::post('/settings','update_theme_settings');
    });

    /* ------------------------------------------
     PAGES MANAGE ROUTES
   -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PagesController::class)->prefix('pages')->group(function (){
        Route::get('/','all_pages')->name('admin.pages');
        Route::get('/new','create_page')->name('admin.pages.create');
        Route::post('/new','store_new_page');
        Route::get('/edit/{id}','edit_page')->name('admin.pages.edit');
        Route::get('/page-builder/{id}','page_builder')->name('admin.pages.builder');
        Route::post('/update','update')->name('admin.pages.update');
        Route::post('/delete/{id}','delete')->name('admin.pages.delete');
        Route::get('/download/{id}', 'download')->name('admin.pages.download');
        Route::post('/upload', 'upload')->name('admin.pages.upload');
    });


    //Others Page Settings
    Route::prefix('error')->controller(\App\Http\Controllers\Landlord\Admin\Error404PageManage::class)->group(function () {
        Route::get('/404-page-manage', 'error_404_page_settings')->name('admin.404.page.settings');
        Route::post('/404-page-manage', 'update_error_404_page_settings');
    });
    Route::prefix('maintenance')->controller(\App\Http\Controllers\Landlord\Admin\MaintainsPageController::class)->group(function () {
        Route::get('/settings', 'maintains_page_settings')->name('admin.maintains.page.settings');
        Route::post('/settings', 'update_maintains_page_settings');
    });


    /* ------------------------------------------
     PRICE PLAN MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PricePlanController::class)->prefix('price-plan')->group(function () {
        Route::get('/', 'all_price_plan')->name('admin.price.plan');
        Route::get('/new', 'create_price_plan')->name('admin.price.plan.create');
        Route::post('/new', 'store_new_price_plan');
        Route::get('/edit/{id}', 'edit_price_plan')->name('admin.price.plan.edit');
        Route::get('/page-builder/{id}', 'price_plan_builder')->name('admin.price.plan.builder');
        Route::post('/update', 'update')->name('admin.price.plan.update');
        Route::post('/delete/{id}', 'delete')->name('admin.price.plan.delete');
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | TESTIMONIAL  ROUTES
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\TestimonialController::class)->prefix('testimonial')->group(function () {
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
    Route::controller(\App\Http\Controllers\Landlord\Admin\BrandController::class)->prefix('brands')->group(function () {
        Route::get('/', 'index')->name('admin.brands');
        Route::post('/', 'store');
        Route::post('/update', 'update')->name('admin.brands.update');
        Route::post('/delete/{id}', 'delete')->name('admin.brands.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.brands.bulk.action');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | BLOG  ROUTES
    |----------------------------------------------------------------------------------------------------------------------------*/

    Route::controller(\Modules\Blog\Http\Controllers\Landlord\Admin\BlogController::class)->prefix('blog')->group(function () {
        Route::get('/', 'index')->name('admin.blog');
        Route::get('/new', 'new_blog')->name('admin.blog.new');
        Route::post('/new', 'store_new_blog');
        Route::get('/edit/{id}', 'edit_blog')->name('admin.blog.edit');
        Route::post('/update/{id}', 'update_blog')->name('admin.blog.update');
        Route::post('/clone', 'clone_blog')->name('admin.blog.clone');
        Route::post('/delete/all/lang/{id}', 'delete_blog_all_lang')->name('admin.blog.delete.all.lang');
        Route::post('/bulk-action', 'bulk_action_blog')->name('admin.blog.bulk.action');
        Route::get('/view/analytics/{id}', 'view_analytics')->name('admin.blog.view.analytics');
        Route::post('/view/data/monthly', 'view_data_monthly')->name('admin.blog.view.data.monthly');

        //Blog Comments Route
        Route::get('/comments/view/{id}', 'view_comments')->name('admin.blog.comments.view');
        Route::post('/comments/delete/all/lang/{id}', 'delete_all_comments')->name('admin.blog.comments.delete.all.lang');
        Route::post('/comments/bulk-action', 'bulk_action_comments')->name('admin.blog.comments.bulk.action');

        // Page Settings
        Route::get('/settings', 'blog_settings')->name('admin.blog.settings');
        Route::post('/settings', 'update_blog_settings');

    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND BLOG CATEGORY AREA
    |----------------------------------------------------------------------------------------------------------------------------*/

    Route::controller(\Modules\Blog\Http\Controllers\Landlord\Admin\BlogCategoryController::class)->prefix('blog-category')->group(function () {
        Route::get('/', 'index')->name('admin.blog.category');
        Route::post('/store', 'new_category')->name('admin.blog.category.store');
        Route::post('/update', 'update_category')->name('admin.blog.category.update');
        Route::post('/delete/all/lang/{id}', 'delete_category_all_lang')->name('admin.blog.category.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.blog.category.bulk.action');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND COUNTRY MANAGE AREA
    |----------------------------------------------------------------------------------------------------------------------------*/
    // tenant.admin.state.by.country
    Route::group(['as' => 'admin.'], function () {
        /*-----------------------------------
            COUNTRY ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'country', "as" => "country."], function () {
            Route::controller(CountryManageController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });

        /*-----------------------------------
            STATE ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::controller(StateController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
                Route::get('country-state', 'getStateByCountry')->name('by.country');
                Route::post('mutliple-country-state', 'getMultipleStateByCountry')->name('by.multiple.country');
            });
        });
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND PRODUCT TAX MANAGE AREA
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::group(['prefix' => 'tax', 'as' => 'admin.tax.'], function () {
        /*-----------------------------------
                COUNTRY TAX ROUTES
         ------------------------------------*/
        Route::group(['prefix' => 'country', 'as' => 'country.'], function () {
            Route::controller(CountryTaxController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });
        /*-----------------------------------
            STATE TAX ROUTES
        ------------------------------------*/
        Route::group(['prefix' => 'state', 'as' => 'state.'], function () {
            Route::controller(StateTaxController::class)->group(function () {
                Route::get('/', 'index')->name('all');
                Route::post('new', 'store')->name('new');
                Route::post('update', 'update')->name('update');
                Route::post('delete/{item}', 'destroy')->name('delete');
                Route::post('bulk-action', 'bulk_action')->name('bulk.action');
            });
        });
    });

    /*----------------------------------------------------------------------------------------------------------------------------
    | BACKEND NEWSLETTER AREA
    |---------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\NewsletterController::class)->prefix('newsletter')->group(function () {
        Route::get('/', 'index')->name('admin.newsletter');
        Route::post('/delete/{id}', 'delete')->name('admin.newsletter.delete');
        Route::post('/single', 'send_mail')->name('admin.newsletter.single.mail');
        Route::get('/all', 'send_mail_all_index')->name('admin.newsletter.mail');
        Route::post('/all', 'send_mail_all');
        Route::post('/new', 'add_new_sub')->name('admin.newsletter.new.add');
        Route::post('/bulk-action', 'bulk_action')->name('admin.newsletter.bulk.action');
        Route::post('/newsletter/verify-mail-send', 'verify_mail_send')->name('admin.newsletter.verify.mail.send');
    });

    /*==============================================
           FORM BUILDER ROUTES
    ==============================================*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\CustomFormBuilderController::class)->prefix('custom-form-builder')->group(function () {
        /*-------------------------
                CUSTOM FORM BUILDERs
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
    -------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\FormBuilderController::class)->prefix('form-builder')->group(function () {
        Route::get('/contact-form', 'contact_form_index')->name('admin.form.builder.contact');
        Route::post('/contact-form', 'update_contact_form');
    });

    Route::controller(\App\Http\Controllers\Tenant\Admin\MenuController::class)->prefix('menu')->group(function () {
        //MENU MANAGE
        Route::get('/menu', 'index')->name('admin.menu');
        Route::post('/new-menu', 'store_new_menu')->name('admin.menu.new');
        Route::get('/menu-edit/{id}', 'edit_menu')->name('admin.menu.edit');
        Route::post('/menu-update/{id}', 'update_menu')->name('admin.menu.update');
        Route::post('/menu-delete/{id}', 'delete_menu')->name('admin.menu.delete');
        Route::post('/menu-default/{id}', 'set_default_menu')->name('admin.menu.default');
        Route::post('/mega-menu', 'mega_menu_item_select_markup')->name('admin.mega.menu.item.select.markup');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
     | CONTACT MESSAGE AREA ROUTES
     |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Landlord\Admin\ContactMessageController::class)->prefix('contact-message')->group(function () {
        Route::get('/', 'index')->name('admin.contact.message.all');
        Route::get('/view/{id}', 'view')->name('admin.contact.message.view');
        Route::post('/delete/{id}', 'delete')->name('admin.contact.message.delete');
        Route::post('/bulk-action', 'bulk_action')->name('admin.contact.message.bulk.action');
    });

    /* ------------------------------------------
    WIDGET BUILDER ROUTES
-------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\WidgetsController::class)->prefix('tenant')->group(function () {
        Route::get('/widgets', 'index')->name('admin.widgets');
        Route::post('/widgets/create', 'new_widget')->name('admin.widgets.new');
        Route::post('/widgets/markup', 'widget_markup')->name('admin.widgets.markup');
        Route::post('/widgets/update', 'update_widget')->name('admin.widgets.update');
        Route::post('/widgets/update/order', 'update_order_widget')->name('admin.widgets.update.order');
        Route::post('/widgets/delete', 'delete_widget')->name('admin.widgets.delete');
    });

    /*==============================================
       SUPPORT TICKET MODULE
    ==============================================*/
    Route::controller(\Modules\SupportTicket\Http\Controllers\Tenant\Admin\SupportTicketController::class)->prefix('support-ticket')->group(function () {
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
    Route::controller(\Modules\SupportTicket\Http\Controllers\Tenant\Admin\SupportDepartmentController::class)->prefix('support-department')->group(function () {
        Route::get('/', 'category')->name('admin.support.ticket.department');
        Route::post('/', 'new_category');
        Route::post('/delete/{id}', 'delete')->name('admin.support.ticket.department.delete');
        Route::post('/update', 'update')->name('admin.support.ticket.department.update');
        Route::post('/bulk-action', 'bulk_action')->name('admin.support.ticket.department.bulk.action');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | ADVERTISEMENT ROUTES
    |----------------------------------------------------------------------------------------------------------------------------*/
        Route::controller(\App\Http\Controllers\Tenant\Admin\AdvertisementController::class)->prefix('advertisement')->group(function(){
            Route::get('/','index')->name('admin.advertisement');
            Route::get('/new','new_advertisement')->name('admin.advertisement.new');
            Route::post('/store','store_advertisement')->name('admin.advertisement.store');
            Route::get('/edit/{id}','edit_advertisement')->name('admin.advertisement.edit');
            Route::post('/update/{id}','update_advertisement')->name('admin.advertisement.update');
            Route::post('/delete/{id}','delete_advertisement')->name('admin.advertisement.delete');
            Route::post('/bulk-action', 'bulk_action')->name('admin.advertisement.bulk.action');
        });
    /* ------------------------------------------
    OTHER SETTINGS ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Tenant\Admin\OtherSettingsController::class)->prefix('tenant')->group(function () {
        Route::get('/settings', 'other_settings_page')->name('admin.other.settings');
        Route::post('/settings', 'update_other_settings');
    });

    /* ------------------------------------------
      TENANT PACKAGE ORDER ROUTES
     -------------------------------------------- */
    Route::prefix('my')->controller(\App\Http\Controllers\Tenant\Admin\MyPackageOrderController::class)->group(function () {
        Route::get('/package-orders', 'my_payment_logs')->name('my.package.order.payment.logs');
        Route::post('/package-order/cancel/{id}', 'package_order_cancel')->name('admin.package.order.cancel');
        Route::post('/package/generate-invoice', 'generate_package_invoice')->name('my.package.invoice.generate');
        Route::post('/package/renew-process', 'package_renew_process')->name('admin.my.package.renew.process');
        Route::get('/package-payment-history/{tenant}', 'package_payment_history')->name('admin.my.package.payment.log.history');
    });

    /* ------------------------------------------
      USER MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Tenant\Admin\FrontendUserManageController::class)->prefix('user')->group(function () {
        Route::get('/', 'all_users')->name('admin.user');
        Route::get('/new', 'new_user')->name('admin.user.new');
        Route::post('/new', 'new_user_store');
        Route::get('/edit-profile/{id}', 'edit_profile')->name('admin.user.edit.profile');
        Route::post('/update-profile', 'update_edit_profile')->name('admin.user.update.profile');
        Route::post('/delete/{id}', 'delete')->name('admin.user.delete');
        Route::post('/change-password', 'update_change_password')->name('admin.user.change.password');
        Route::get('/view/{id}', 'view_profile')->name('admin.user.view');
        Route::post('/send-mail', 'send_mail')->name('admin.user.send.mail');
        Route::post('/resend-verify-mail', 'resend_verify_mail')->name('admin.user.resend.verify.mail');
    });


    /*----------------------------------------------------------------------------------------------------------------------------
    | PACKAGE ORDER MANAGE
    |----------------------------------------------------------------------------------------------------------------------------*/
    Route::controller(\App\Http\Controllers\Tenant\Admin\OrderManageController::class)->prefix('order-manage')->group(function () {
        Route::get('/all', 'all_orders')->name('admin.package.order.manage.all');
        Route::get('/view/{id}', 'view_order')->name('admin.package.order.manage.view');
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
        Route::get('/payment-logs', 'all_payment_logs')->name('admin.payment.logs');
        Route::post('/payment-logs/delete/{id}', 'payment_logs_delete')->name('admin.payment.delete');
        Route::post('/payment-logs/approve/{id}', 'payment_logs_approve')->name('admin.payment.approve');
        Route::post('/payment-logs/bulk-action', 'payment_log_bulk_action')->name('admin.payment.bulk.action');
        Route::get('/payment-logs/report', 'payment_report')->name('admin.payment.report');
        Route::post('/package-user/generate-invoice', 'generate_package_invoice')->name('admin.package.invoice.generate');
    });

    /*------------------------------------------
        MY PACKAGE ORDER MANAGE ROUTES
    -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Tenant\Admin\MyPackageOrderController::class)->prefix('package')->group(function () {
        Route::get('/payment-logs', 'my_payment_logs')->name('my.package.order.payment.logs');
        Route::get('/buy-plan', 'update_other_settings')->name('my.package.order.buy.plan');
    });


    /* ------------------------------------------
PAYMENT SETTINGS ROUTES
-------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PaymentSettingsController::class)->prefix('payment-settings')->group(function (){

        //Currencies
        Route::get('/currency','currency_settings')->name('admin.payment.currency.settings');
        Route::post('/currency','update_currency_settings');

        Route::get('/paypal','paypal_settings')->name('admin.payment.paypal.settings');
        Route::get('/paytm','paytm_settings')->name('admin.payment.paytm.settings');
        Route::get('/stripe','stripe_settings')->name('admin.payment.stripe.settings');
        Route::get('/razorpay','razorpay_settings')->name('admin.payment.razorpay.settings');
        Route::get('/paystack','paystack_settings')->name('admin.payment.paystack.settings');
        Route::get('/mollie','mollie_settings')->name('admin.payment.mollie.settings');
        Route::get('/payfast','payfast_settings')->name('admin.payment.payfast.settings');
        Route::get('/midtrans','midtrans_settings')->name('admin.payment.midtrans.settings');
        Route::get('/cashfree','cashfree_settings')->name('admin.payment.cashfree.settings');
        Route::get('/instamojo','instamojo_settings')->name('admin.payment.instamojo.settings');
        Route::get('/marcadopago','marcadopago_settings')->name('admin.payment.marcadopago.settings');
        Route::get('/zitopay','zitopay_settings')->name('admin.payment.zitopay.settings');
        Route::get('/squareup','squareup_settings')->name('admin.payment.squareup.settings');
        Route::get('/cinetpay','cinetpay_settings')->name('admin.payment.cinetpay.settings');
        Route::get('/paytabs','paytabs_settings')->name('admin.payment.paytabs.settings');
        Route::get('/billplz','billplz_settings')->name('admin.payment.billplz.settings');
        Route::get('/manual_payment','manual_payment_settings')->name('admin.payment.manual_payment.settings');
        Route::get('/flutterwave','flutterwave_settings')->name('admin.payment.flutterwave.settings');
        Route::get('/toyyibpay','toyyibpay_settings')->name('admin.payment.toyyibpay.settings');
        Route::get('/pagali','pagali_settings')->name('admin.payment.pagali.settings');
        Route::get('/authorizenet','authorizenet_settings')->name('admin.payment.authorizenet.settings');
        Route::get('/sitesway','sitesway_settings')->name('admin.payment.sitesway.settings');
        //update
        Route::post('/gateway-update','update_gateway_settings')->name('landlord.admin.payment.gateway.update');
    });


    /* ------------------------------------------
PAYMENT SETTINGS ROUTES
-------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\PaymentSettingsController::class)->prefix('payment-settings')->group(function (){

        //Currencies
        Route::get('/currency','currency_settings')->name('admin.payment.currency.settings');
        Route::post('/currency','update_currency_settings');

        Route::get('/paypal','paypal_settings')->name('admin.payment.paypal.settings');
        Route::get('/paytm','paytm_settings')->name('admin.payment.paytm.settings');
        Route::get('/stripe','stripe_settings')->name('admin.payment.stripe.settings');
        Route::get('/razorpay','razorpay_settings')->name('admin.payment.razorpay.settings');
        Route::get('/paystack','paystack_settings')->name('admin.payment.paystack.settings');
        Route::get('/mollie','mollie_settings')->name('admin.payment.mollie.settings');
        Route::get('/payfast','payfast_settings')->name('admin.payment.payfast.settings');
        Route::get('/midtrans','midtrans_settings')->name('admin.payment.midtrans.settings');
        Route::get('/cashfree','cashfree_settings')->name('admin.payment.cashfree.settings');
        Route::get('/instamojo','instamojo_settings')->name('admin.payment.instamojo.settings');
        Route::get('/marcadopago','marcadopago_settings')->name('admin.payment.marcadopago.settings');
        Route::get('/zitopay','zitopay_settings')->name('admin.payment.zitopay.settings');
        Route::get('/squareup','squareup_settings')->name('admin.payment.squareup.settings');
        Route::get('/cinetpay','cinetpay_settings')->name('admin.payment.cinetpay.settings');
        Route::get('/paytabs','paytabs_settings')->name('admin.payment.paytabs.settings');
        Route::get('/billplz','billplz_settings')->name('admin.payment.billplz.settings');
        Route::get('/bank-transfer','bank_transfer_settings')->name('admin.payment.bank_transfer.settings');
        Route::get('/manual_payment','manual_payment_settings')->name('admin.payment.manual_payment.settings');
        Route::get('/flutterwave','flutterwave_settings')->name('admin.payment.flutterwave.settings');
        Route::get('/toyyibpay','toyyibpay_settings')->name('admin.payment.toyyibpay.settings');
        Route::get('/pagali','pagali_settings')->name('admin.payment.pagali.settings');
        Route::get('/authorizenet','authorizenet_settings')->name('admin.payment.authorizenet.settings');
        Route::get('/sitesway','sitesway_settings')->name('admin.payment.sitesway.settings');
        Route::get('/kinetic','kinetic_settings')->name('admin.payment.kinetic.settings');

        //update
        Route::post('/gateway-update','update_gateway_settings')->name('admin.payment.gateway.update');
    });



    /* ------------------------------------------
            GENERAL SETTINGS ROUTES
         -------------------------------------------- */
    Route::controller(\App\Http\Controllers\Landlord\Admin\GeneralSettingsController::class)->prefix('general-settings')->group(function () {

        //Reading
        Route::get('/page-settings', 'page_settings')->name('admin.general.page.settings');
        Route::post('/page-settings', 'update_page_settings');
        //Navbar Global Variant
        Route::get('/global-variant-navbar', 'global_variant_navbar')->name('admin.general.global.navbar.settings');
        Route::post('/global-variant-navbar', 'update_global_variant_navbar');
        //Footer Global Variant
        Route::get('/global-variant-footer', 'global_variant_footer')->name('admin.general.global.footer.settings');
        Route::post('/global-variant-footer', 'update_global_variant_footer');

        /* Basic settings */
        Route::get('/basic-settings', 'basic_settings')->name('admin.general.basic.settings');
        Route::post('/basic-settings', 'update_basic_settings');
        /* Page Settings */

        Route::get('/page-settings', 'page_settings')->name('admin.general.page.settings');
        Route::post('/page-settings', 'update_page_settings');
        /* site identity Settings */
        Route::get('/site-identity', 'site_identity')->name('admin.general.site.identity');
        Route::post('/site-identity', 'update_site_identity');

        /* Color Settings */
        Route::get('/color-settings', 'color_settings')->name('admin.general.color.settings');
        Route::post('/color-settings', 'update_color_settings');

        /* Typography Settings */
        Route::get('/typography-settings', 'typography_settings')->name('admin.general.typography.settings');
        Route::post('/typography-settings', 'update_typography_settings');
        Route::post('typography-settings/single', 'get_single_font_variant')->name('admin.general.typography.single');

        /* Custom forn Settings */
        Route::post('/add-custom-font','add_custom_font')->name('admin.add.custom.font');
        Route::post('/set-custom-font','set_custom_font')->name('admin.set.custom.font');
        Route::get('/delete-custom-font/{font}','delete_custom_font')->name('admin.custom.font.delete');

        /* SEO Settings */
        Route::get('/seo-settings', 'seo_settings')->name('admin.general.seo.settings');
        Route::post('/seo-settings', 'update_seo_settings');


        /* Third party scripts Settings */
        Route::get('/third-party-script-settings', 'third_party_script_settings')->name('admin.general.third.party.script.settings');
        Route::post('/third-party-script-settings', 'update_third_party_script_settings');

        /* smtp Settings */
        Route::get('/email-settings', 'email_settings')->name('admin.general.email.settings');
        Route::post('/email-settings', 'update_email_settings');
        Route::post('/email-settings/mail', 'send_test_mail')->name('admin.general.mail.settings');


        /* custom css Settings */
        Route::get('/custom-css-settings', 'custom_css_settings')->name('admin.general.custom.css.settings');
        Route::post('/custom-css-settings', 'update_custom_css_settings');

        /* js css Settings */
        Route::get('/custom-js-settings', 'custom_js_settings')->name('admin.general.custom.js.settings');
        Route::post('/custom-js-settings', 'update_custom_js_settings');
        /* Cache  Settings */
        Route::get('/cache-settings', 'cache_settings')->name('admin.general.cache.settings');
        Route::post('/cache-settings', 'update_cache_settings');

        //GDPR Settings
        Route::get('/gdpr-settings', 'gdpr_settings')->name('admin.general.gdpr.settings');
        Route::post('/gdpr-settings', 'update_gdpr_cookie_settings');

        /* Sitemap Settings */
        Route::get('/sitemap-settings','sitemap_settings')->name('admin.general.sitemap.settings');
        Route::post('/sitemap-settings','update_sitemap_settings');
        Route::post('/sitemap-settings/delete', 'delete_sitemap_settings')->name('admin.general.sitemap.settings.delete');

        /* Licennse Upgrade Settings */
        Route::get('/license-settings', 'license_settings')->name('admin.general.license.settings');
        Route::post('/license-settings', 'update_license_settings');

    });

});


