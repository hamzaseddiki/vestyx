<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Events\TenantNotificationEvent;
use App\Events\TenantRegisterEvent;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Mail\BasicDynamicTemplateMail;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\CronjobLog;
use App\Models\CustomDomain;
use App\Models\PackageHistory;
use App\Models\PaymentLogHistory;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\Testimonial;
use App\Models\Themes;
use App\Models\User;
use App\Models\WebsiteInstruction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\EmailTemplate\Traits\EmailTemplate\Landlord\SubscriptionEmailTemplate;
use Spatie\Activitylog\Models\Activity;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;

class TenantInstructionController extends Controller
{

    private const BASE_PATH = 'landlord.admin.website-instruction.';
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function all_instruction(){
        $all_instructions = WebsiteInstruction::select('id','title','description','status','image')->get();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'index',compact('all_instructions','default_lang'));
    }

    public function create_instruction(Request $request){

        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'create',compact('default_lang'));
    }

    public function store_instruction(Request $request)
    {
        $request->validate([
           'title' => 'required|string',
           'description' => 'nullable',
           'image' => 'required',
        ]);

        $repeater_item = $request->repeater_data ?? ['button_text' => ['']];
        $instruction = new WebsiteInstruction();
        return $this->extracted($instruction, $request, $repeater_item);

    }

    public function edit_instruction(Request $request,$id){
        $instruction = WebsiteInstruction::FindOrFail($id);
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'edit',compact('default_lang','instruction'));
    }

    public function update_instruction(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable',
            'image' => 'required',
        ]);

        $repeater_item = $request->repeater_data ?? ['button_text' => ['']];
        $instruction = WebsiteInstruction::findOrFail($id);
        return $this->extracted($instruction, $request, $repeater_item);

    }

    public function extracted($instruction, Request $request, mixed $repeater_item)
    {
        $instruction->setTranslation('title', $request->lang, SanitizeInput::esc_html($request->title));
        $instruction->setTranslation('description', $request->lang, SanitizeInput::esc_html($request->description));

        $instruction->image = $request->image;
        $instruction->status = $request->status;
        $instruction->repeater_data = serialize($repeater_item);
        $instruction->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete_instruction($id)
    {
        $instruction = WebsiteInstruction::findOrFail($id);
        $instruction->delete();
        return response()->success(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        $all = WebsiteInstruction::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

}
