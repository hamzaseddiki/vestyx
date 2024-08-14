<?php

namespace Database\Seeders\Tenant\ModuleData;

use App\Models\FormBuilder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormBuilderSeed extends Seeder
{
    public static function execute()
    {
        DB::statement("INSERT INTO `form_builders` (`id`, `title`, `email`, `button_text`, `fields`, `success_message`, `created_at`, `updated_at`)
VALUES
	(1,'{\"en_US\":\"Contact Form\",\"ar\":\"\\u0646\\u0645\\u0648\\u0630\\u062c \\u0627\\u0644\\u0627\\u062a\\u0635\\u0627\\u0644\"}','amranikamel016@gmail.com','{\"en_US\":\"Send Message\",\"ar\":\"\\\u0623\\\u0631\\\u0633\\\u0644 \\\u0631\\\u0633\\\u0627\\\u0644\\\u0629\"}','{\"lang\":\"ar\",\"success_message\":\"\\\u0634\\\u0643\\\u0631\\\u0627 \\\u0639\\\u0644\\\u0649 \\\u0631\\\u0633\\\u0627\\\u0644\\\u062a\\\u0643\",\"field_type\":[\"text\",\"text\",\"email\",\"text\",\"file\",\"textarea\"],\"field_name\":[\"name\",\"subject\",\"email\",\"call\",\"file\",\"message\"],\"field_placeholder\":[\"Your Name\",\"Subjet\",\"Email\",\"Talephone\",\"File\",\"Message\"],\"field_required\":[\"on\"],\"mimes_type\":{\"4\":\"mimes:jpg,jpeg,png\"}}','{\"en_US\":\"Thanx for your message\",\"ar\":\"\\u0634\\\u0643\\\u0631\\\u0627 \\\u0639\\\u0644\\\u0649 \\\u0631\\\u0633\\\u0627\\\u0644\\\u062a\\\u0643\"}','2022-08-31 05:02:00','2023-01-30 06:46:57')");
    }
}
