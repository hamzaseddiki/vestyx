<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\DataTableHelpers\General;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\CustomDomain;
use App\Models\PackageHistory;
use App\Models\PaymentLogHistory;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use App\Models\TenantException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;


class TenantExceptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function website_issues()
    {
        $all_issues = TenantException::where(['domain_create_status'=> 0, 'seen_status' => 0])->orderBy('id','desc')->get();
        return view('landlord.admin.user-website-issues.all-issues', compact('all_issues'));
    }

    public function generate_domain(Request $request)
    {

        $id = $request->id;

        $exception = TenantException::findOrFail($id);
        $tenant = Tenant::find($exception->tenant_id);

        if(is_null($tenant)){
            return response()->danger(__('tenant did not found'));
        }

        $payment_log = PaymentLogs::where('tenant_id',$tenant->id)->first();
        if(is_null($payment_log)){
            return response()->danger(__('tenant payment log not found'));
        }

        $payment_data = [];
        $payment_data['order_id'] = $payment_log->id;
        LandlordPricePlanAndTenantCreate::update_tenant($payment_data); //tenant table user_id update and expired date update
        LandlordPricePlanAndTenantCreate::update_database($payment_log->id, $payment_log->transaction_id); //update payment log  information with transaction id


        try{
            $tenant->database()->manager()->createDatabase($tenant);

        }catch(\Exception $e){

            //todo check str_contains database exists
            $message = $e->getMessage();

            if(!\str_contains($message,'database exists')){
                return response()->danger(__('Database created failed, Make sure your database user has permission to create database'));
            }

            if(\str_contains($message,'database exists')){
                return response()->danger(__('Data already Exists'));
            }

        }

        try{
            $tenant->domains()->create(['domain' => $tenant->getTenantKey().'.'.getenv('CENTRAL_DOMAIN')]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            if(!str_contains($message,'occupied by another tenant')){
                return response()->danger(__('subdomain create failed'));
            }
        }

        try{
            //database migrate
            $command = 'tenants:migrate --force --tenants='.$tenant->id;
            Artisan::call($command);
        }catch(\Exception $e){
            return response()->danger(__('tenant database migrate failed'));
        }

        try{
            Artisan::call('tenants:seed', [
                '--tenants' => $tenant->getTenantKey(),
                '--force' => true
            ]);

            $exception->domain_create_status = 1;
            $exception->seen_status = 1;
            $exception->save();

            LandlordPricePlanAndTenantCreate::tenant_create_event_with_credential_mail($payment_log->id,false);
            LandlordPricePlanAndTenantCreate::send_order_mail($payment_log->id);

            return response()->success(ResponseMessage::SettingsSaved('Database and domain create success'));


        }catch(\Exception $e){

            //Duplicate entry
            $message = $e->getMessage();
            if(str_contains($message,'Duplicate entry')){
                $exception->domain_create_status = 1;
                $exception->seen_status = 1;
                $exception->save();
                return response()->danger(__('tenant database demo data already imported'));

            }
            //todo, tested in user shared hosting website...
            //this code is work fine in shared hosting, without change of database engine

            if(str_contains($message,'Connection could not be established with host')){
                return response()->success(__('tenant database migrate and import success and website is ready to use, mail not send to user because your smtp not working.'));
            }
        }


        return response()->success(ResponseMessage::SettingsSaved('Database and domain create success'));
    }


    public function set_database_manually(Request $request)
    {

        $request->validate([
            'manual_database_name' => 'required|string|max:191'
        ]);


        $id = $request->id;
        $manual_database = $request->manual_database_name;

        $exception = TenantException::findOrFail($id);
        $tenant = Tenant::find($request->domain);


        if(is_null($tenant)){
            return response()->danger(__('tenant did not found'));
        }

        if(!empty($tenant) && $tenant->id == $manual_database){
            return response()->danger(__('Database exists with this name'));
        }


        $payment_log = PaymentLogs::where('tenant_id',$tenant->id)->first();
        if(is_null($payment_log)){
            return response()->danger(__('tenant payment log not found'));
        }

        $payment_data = [];
        $payment_data['order_id'] = $payment_log->id;
        LandlordPricePlanAndTenantCreate::update_tenant($payment_data); //tenant table user_id update and expired date update
        LandlordPricePlanAndTenantCreate::update_database($payment_log->id, $payment_log->transaction_id); //update payment log  information with transaction id

        try{

            $current_tenant = \DB::table('tenants')->where('id',$tenant->id)->first();
            $format = (array) json_decode($current_tenant->data);
            $format['tenancy_db_name'] = $manual_database;
            \Illuminate\Support\Facades\DB::table('tenants')->where('id',$tenant->id)->update(['data'=> json_encode($format)]);

        }catch(\Exception $e){

            // todo check str_contains database exists
            $message = $e->getMessage();


            if(\str_contains($message,'Access denied')){
                return response()->danger(__('Wrong database or your user privilege has not been set'));
            }


            if(\str_contains($message,'database exists')){
                return response()->danger(__('Data already Exists'));
            }

        }

        try{
            $tenant->domains()->create(['domain' => $tenant->getTenantKey().'.'.getenv('CENTRAL_DOMAIN')]);
        }catch(\Exception $e){
            $message = $e->getMessage();
            if(!str_contains($message,'occupied by another tenant')){
                return response()->danger(__('subdomain create failed'));
            }
        }

        try{
            //database migrate
            $command = 'tenants:migrate --force --tenants='.$tenant->id;
            Artisan::call($command);
        }catch(\Exception $e){
            return response()->danger(__('tenant database migrate failed'));
        }

        try{
            Artisan::call('tenants:seed', [
                '--tenants' => $tenant->getTenantKey(),
                '--force' => true
            ]);

            $exception->domain_create_status = 1;
            $exception->seen_status = 1;
            $exception->save();

            LandlordPricePlanAndTenantCreate::tenant_create_event_with_credential_mail($payment_log->id,false);
            LandlordPricePlanAndTenantCreate::send_order_mail($payment_log->id);

            return response()->success(ResponseMessage::SettingsSaved('Database and domain create success'));


        }catch(\Exception $e){

            //Duplicate entry
            $message = $e->getMessage();
            if(str_contains($message,'Duplicate entry')){
                $exception->domain_create_status = 1;
                $exception->seen_status = 1;
                $exception->save();
                return response()->danger(__('tenant database demo data already imoported'));

            }
            return response()->danger(__('tenant database demo data import failed'));
        }

        $exception->domain_create_status = 1;
        $exception->seen_status = 1;
        $exception->save();

        LandlordPricePlanAndTenantCreate::tenant_create_event_with_credential_mail($payment_log->id,false);
        LandlordPricePlanAndTenantCreate::send_order_mail($payment_log->id);
        return response()->success(ResponseMessage::SettingsSaved('Database and domain create success'));
    }


    public function issue_delete($id)
    {
        TenantException::find($id)->delete();
        return response()->success(ResponseMessage::SettingsSaved('Issue Deleted Successfully..!'));
    }


    public function all_website_list (Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        if ($request->ajax()){
            $data = Tenant::select('*')->orderBy('id','desc');

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('username',function ($row) use ($default_lang){
                   return $row->payment_log?->user?->name;
                })
                ->addColumn('subdomain',function ($row){
                    return $row->id;
                })
                ->addColumn('domain',function ($row) use($default_lang){
                    return General::get_domain_name($row);
                })
                ->addColumn('browse',function($row){
                    return General::browse_tenant_sites($row);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $action.= General::deletePopover(route('landlord.admin.tenant.website.delete',$row->id));
                    return $action;
                })
                ->rawColumns(['checkbox','domain','browse','action'])
                ->make(true);
        }

        return view('landlord.admin.user-website-issues.all-website-list', compact('default_lang'));
    }

    public function delete_website($id)
    {

        $tenant = Tenant::findOrFail($id);
        $user_id = $tenant->user_id;

        $tenant_custom_font_path = 'assets/tenant/frontend/custom-fonts/'.$tenant->id.'/';
        if(\File::exists($tenant_custom_font_path) && is_dir($tenant_custom_font_path)){
            File::deleteDirectory($tenant_custom_font_path);
        }

        //dynamic assets delete
        if(file_exists('assets/tenant/frontend/themes/css/dynamic-styles/'.$tenant->id.'-style.css')){
            unlink('assets/tenant/frontend/themes/css/dynamic-styles/'.$tenant->id.'-style.css');

        }

        if(file_exists('assets/tenant/frontend/themes/js/dynamic-scripts/'.$tenant->id.'-script.js')){
            unlink('assets/tenant/frontend/themes/js/dynamic-scripts/'.$tenant->id.'-script.js');
        }

        $path = 'assets/tenant/uploads/media-uploader/'.$tenant->id;
        CustomDomain::where([['old_domain', $tenant->id], ['custom_domain_status', '!=','connected']])
            ->orWhere([['custom_domain', $tenant->id], ['custom_domain_status', '==', 'connected']])->delete();

        PaymentLogs::where('tenant_id',$tenant->id)->delete();
        PaymentLogHistory::where('tenant_id',$tenant->id)->delete();
        PackageHistory::where('user_id',$user_id)->delete();


        if(!empty($tenant)){

            try{
                $tenant->domains()->delete();
                $tenant->delete();
            }catch(\Exception $ex){

                $message = $ex->getMessage();

                if(str_contains($message,'Access denied')){
                    return response()->danger(ResponseMessage::delete('Make sure your database user has permission to delete domain & database'));
                }

                if(str_contains($message,'drop database')){
                    return response()->danger(ResponseMessage::delete('Data deleted'));
                }
            }


        }
        if(\File::exists($path) && is_dir($path)){
            File::deleteDirectory($path);
        }



        $check_tenant = Tenant::where('user_id', $user_id)->count();
        if ($check_tenant >! 0)
        {
            User::findOrFail($user_id)->update(['has_subdomain' => false]);
        }

        return response()->danger(ResponseMessage::delete(__('Tenant deleted successfully..!')));
    }

}
