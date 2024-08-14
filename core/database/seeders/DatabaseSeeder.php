<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\PaymentGateway;
use App\Models\Tenant;
use App\Models\Themes;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Modules\CountryManage\Entities\Country;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        setEnvValue(['QUEUE_CONNECTION' => 'database']);

        $this->landlord_new_theme_data_seed();

        //Theme auto code set
        if(get_static_option_central('auto_theme_slug_update_status') != 1){
            $this->update_theme_slug_and_code();
            $this->update_old_theme_slug();
        }
        //Theme auto code set


        $this->static_data_or_switcher_update();


        $this->landlord_new_payment_gateway_seed();

        //for custom domain request email sent from tenant admin
        update_static_option_central('site_global_email',get_static_option('site_global_email'));

        //For new permission store
        $this->landlord_new_permission_seed();

        //new tables exists or not
        $this->check_tables_exists();

        //Generating tenant table token for direct login from landlord user dashboard
        $this->generating_token_for_login_action();


    }


    private function landlord_new_theme_data_seed()
    {
        $theme_json_path = json_decode(file_get_contents('assets/landlord/admin/demo-theme-asset/json-data/all-themes.json'));
        $existing_database_themes = Themes::pluck('id')->toArray();

        foreach ($theme_json_path as $item){
            foreach ($item as $data){

                if(!in_array($data->id,$existing_database_themes )){

                    $ti = json_decode($data->title);
                    $desc = json_decode($data->description);

                    $title_condition_eng = $ti->en_GB ?? '';
                    $title_condition_ar= $ti->ar ?? '';

                    $desc_condition_eng = $desc->en_GB ?? '';
                    $desc_condition_ar= $desc->ar ?? '';

                    \App\Models\Themes::updateOrCreate([
                        'title' => [
                            'en_GB' => $title_condition_eng,
                            'ar' => $title_condition_ar,
                        ],
                        'description' => [
                            'en_GB' => $desc_condition_eng,
                            'ar' => $desc_condition_ar,
                        ],

                        'slug' => $data->slug,
                        'image' => null,
                        'status' => $data->status,
                        'is_available' => $data->is_available,
                        'url' => $data->url,
                    ]);


                }
            }
        }
    }

    private function update_theme_slug_and_code()
    {
        $this->update_action_theme_slug_and_code('donation','Donation', 'Donation description','on');
        $this->update_action_theme_slug_and_code('job-find','Job Find', 'Job finding description','on');
        $this->update_action_theme_slug_and_code('event','Event', 'Event description','on');
        $this->update_action_theme_slug_and_code('support-ticketing','Support Ticket', 'Support ticket description','on');
        $this->update_action_theme_slug_and_code('article-listing','Article', 'Article description','on');
        $this->update_action_theme_slug_and_code('eCommerce','Ecommerce', 'Ecommerce shop description','on');
        $this->update_action_theme_slug_and_code('construction','Construction', 'Construction description','on');
        $this->update_action_theme_slug_and_code('consultancy','Consultancy', 'Consultancy description','on');
        $this->update_action_theme_slug_and_code('agency','Agency', 'Agency description','on');
        $this->update_action_theme_slug_and_code('newspaper','Newspaper', 'Newspaper description','on');
        $this->update_action_theme_slug_and_code('portfolio','Portfolio', 'Portfolio description','on');
        $this->update_action_theme_slug_and_code('software-business','Software Business', 'Software business description','on');
        $this->update_action_theme_slug_and_code('wedding','Wedding', 'Wedding description','on');
        $this->update_action_theme_slug_and_code('photography','Photography', 'Photography description','on');
        $this->update_action_theme_slug_and_code('barber-shop','Barber Shop', 'Barber shop description','on');
        $this->update_action_theme_slug_and_code('hotel-booking','Hotel Booking', 'Hotel booking theme',NULL);
        $this->update_action_theme_slug_and_code('course','Course', 'Course management theme',NULL);
    }

    private function update_action_theme_slug_and_code($slug,$title,$description,$available)
    {
        $lang = default_lang();
        $name_field = 'theme_name_'.$lang;
        $description_field = 'theme_description_'.$lang;
        $available_field = 'theme_is_available';

        update_static_option_central($slug.'_'.$name_field,$title);
        update_static_option_central($slug.'_'.$description_field,$description);
        update_static_option_central($slug.'_'.$available_field,$available);
    }

    private function landlord_new_payment_gateway_seed()
    {
        $gateway_json_path = json_decode(file_get_contents('assets/common/common-json-data/payment-gateways.json'));
        $existing_database_gateway = PaymentGateway::pluck('name')->toArray();


        foreach ($gateway_json_path as $item){
            foreach ($item as $data){

                if($data->name == 'manual_payment_'){
                    PaymentGateway::where('name',$data->name)->update(['name' => 'bank_transfer']);
                }

                if(!in_array($data->name,$existing_database_gateway)){
                    PaymentGateway::UpdateOrCreate([
                        'name' => $data->name,
                        'image' => $data->image,
                        'description' => $data->description,
                        'status' => $data->status,
                        'test_mode' => $data->test_mode,
                        'credentials' => $data->credentials,
                    ]);
                }
            }
        }
    }

    private function landlord_new_permission_seed()
    {
        $permissions = [
            'user-website-issue-list','dashboard','wallet-list','wallet-history','theme-list','theme-create','theme-edit','theme-delete',
            'notification-list','coupon-list','coupon-create','coupon-edit','coupon-delete','custom-domain','appearance-widget-builder',
            'appearance-menu-manage','appearance-country-list','appearance-404-settings','appearance-topbar-settings','appearance-email-template',
            'appearance-login-register-settings','appearance-maintenance-settings','user-website-instruction-list','user-website-instruction-create',
            'user-website-instruction-edit','user-website-instruction-delete','other-demo-data','page-builder-demo-data','plugin-manage'
        ];

        foreach ($permissions as $permission){
            \Spatie\Permission\Models\Permission::updateOrCreate(['name' => $permission,'guard_name' => 'admin']);
        }
    }

    private function check_tables_exists()
    {
        if(!Schema::hasTable('blogs')){
            Schema::create('blogs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('admin_id');
                $table->text('title');
                $table->text('slug')->nullable();
                $table->longText('blog_content');
                $table->string('image');
                $table->string('author')->nullable();
                $table->string('excerpt')->nullable();
                $table->string('tags')->nullable();
                $table->string('image_gallery')->nullable();
                $table->string('views')->nullable();
                $table->text('video_url')->nullable();
                $table->string('visibility')->nullable();
                $table->string('featured')->nullable();
                $table->string('created_by')->nullable();
                $table->boolean('status')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }


        if(!Schema::hasTable('blog_categories')){
            Schema::create('blog_categories', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }

        if(!Schema::hasTable('blog_comments')){
            Schema::create('blog_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('commented_by');
                $table->longText('comment_content');
                $table->timestamps();
            });
        }

    }

    private function generating_token_for_login_action(){
        $all_user = User::all();
        foreach ($all_user as $user){
            DB::table('tenants')->where('user_id',$user->id)->update([
                'unique_key' => Hash::make(Str::random(32))
            ]);
        }
    }

    private function static_data_or_switcher_update()
    {
        update_static_option('section_title_extra_design_status','on');
        update_static_option('landlord_frontend_contact_info_show_hide','on');
        update_static_option('landlord_frontend_social_info_show_hide','on');
        update_static_option('landlord_frontend_language_show_hide','on');
    }

    private function update_old_theme_slug()
    {
        $this->update_action_old_theme_slug('theme-1','donation');
        $this->update_action_old_theme_slug('theme-2','job-find');
        $this->update_action_old_theme_slug('theme-3','event');
        $this->update_action_old_theme_slug('theme-4','support-ticketing');
        $this->update_action_old_theme_slug('theme-6','article-listing');
        $this->update_action_old_theme_slug('theme-5','eCommerce');
        $this->update_action_old_theme_slug('theme-9','construction');
        $this->update_action_old_theme_slug('theme-10','consultancy');
        $this->update_action_old_theme_slug('theme-7','agency');
        $this->update_action_old_theme_slug('theme-8','newspaper');
        $this->update_action_old_theme_slug('theme-13','portfolio');
        $this->update_action_old_theme_slug('theme-14','software-business');
        $this->update_action_old_theme_slug('theme-11','wedding');
        $this->update_action_old_theme_slug('theme-12','photography');
        $this->update_action_old_theme_slug('theme-15','barber-shop');

        //for auto
        update_static_option_central('auto_theme_slug_update_status',1);
    }

    private function update_action_old_theme_slug($old_slug,$new_slug)
    {
        Tenant::where('theme_slug',$old_slug)->update(['theme_slug' => $new_slug]);
    }



}
