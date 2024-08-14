<?php

namespace Database\Seeders\Tenant\ModuleData\Appointment;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Appointment\Entities\AdditionalAppointment;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Appointment\Entities\AppointmentDayType;
use Modules\Appointment\Entities\AppointmentSchedule;
use Modules\Appointment\Entities\AppointmentSubcategory;
use Modules\Appointment\Entities\AppointmentTax;
use Modules\Appointment\Entities\SubAppointment;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\ChildCategory;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\DeliveryOption;
use Modules\Attributes\Entities\Size;
use Modules\Attributes\Entities\SubCategory;
use Modules\Badge\Entities\Badge;
use Modules\Campaign\Entities\Campaign;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Modules\Product\Entities\ProductChildCategory;
use Modules\Product\Entities\ProductShippingReturnPolicy;
use Modules\Product\Entities\ProductSubCategory;

class AppointmentDataSeed
{
    public static function execute()
    {
        self::category();
        self::sub_category();
        self::day_type();
        self::days();
        self::schedule();
        self::tax();
        self::sub_appointments();
        self::appointments();
        self::additional_appointments();
    }

    private static function category()
    {
        $object = new JsonDataModifier('appointment','category');
        $data = $object->getColumnData(['id', 'title', 'status']);
        AppointmentCategory::insert($data);
    }

    private static function sub_category()
    {
        $object = new JsonDataModifier('appointment','sub-category');
        $data = $object->getColumnData(['appointment_category_id', 'title', 'status']);
        AppointmentSubcategory::insert($data);
    }

    private static function day_type()
    {
        $object = new JsonDataModifier('appointment','day-type');
        $data = $object->getColumnData([ 'title', 'status']);
        AppointmentDayType::insert($data);
    }

    private static function days()
    {
        $object = new JsonDataModifier('appointment','days');
        $data = $object->getColumnData([ 'day','key', 'status']);
        AppointmentDay::insert($data);
    }

    private static function schedule()
    {
        $object = new JsonDataModifier('appointment','schedule');
        $data = $object->getColumnData([ 'day_id','time','allow_multiple', 'status','day_type']);
        AppointmentSchedule::insert($data);
    }

    private static function tax()
    {
        $object = new JsonDataModifier('appointment','tax');
        $data = $object->getColumnData([ 'appointment_id','tax_type','tax_amount']);
        AppointmentTax::insert($data);
    }

    private static function sub_appointments()
    {
        $object = new JsonDataModifier('appointment','sub-appointment');
        $data = $object->getColumnData([
            'title',
            'slug',
            'description',
            'image',
            'price',
            'person',
            'status',
            'views',
            'is_popular',
        ]);
        SubAppointment::insert($data);
    }

    private static function appointments()
    {
        $object = new JsonDataModifier('appointment','appointment');
        $data = $object->getColumnData([
            'appointment_category_id',
            'appointment_subcategory_id',
            'title',
            'slug',
            'description',
            'image',
            'price',
            'person',
            'views',
            'is_popular',
            'status',
            'sub_appointment_status',
            'tax_status',
        ]);
        Appointment::insert($data);
    }

    private static function additional_appointments()
    {
        $object = new JsonDataModifier('appointment','additional-appointment');
        $data = $object->getColumnData([
            'appointment_id',
            'sub_appointment_id',
        ]);
        AdditionalAppointment::insert($data);
    }
}
