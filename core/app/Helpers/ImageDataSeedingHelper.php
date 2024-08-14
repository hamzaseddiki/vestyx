<?php

namespace App\Helpers;

use App\Models\Admin;
use App\Models\MediaUploader;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageDataSeedingHelper
{

    public static function insert($file_path)
    {
        $subdomain = '';

        if(tenant()){
            $tenant_user = tenant()->user()->first() ?? [];
            $subdomain = $tenant_user->subdomain.'/';
        }

        $folder_list = ['grid','large','thumb'];
        if (!file_exists('assets/tenant/uploads/media-uploader/'.$subdomain) && !is_dir('assets/tenant/uploads/media-uploader/'.$subdomain)){
            if(mkdir('assets/tenant/uploads/media-uploader/'.$subdomain)){
                foreach ($folder_list as $flname){
                    if (!file_exists('assets/tenant/uploads/media-uploader/'.$subdomain.'/'.$flname) && !is_dir('assets/tenant/uploads/media-uploader/'.$subdomain.'/'.$flname)) {
                        mkdir('assets/tenant/uploads/media-uploader/' . $subdomain.'/'.$flname);
                    }
                }
            }
        }

        $file = $file_path;

        if (!empty($file)) {

            $image = $file;
            $images = pathinfo($file_path);

            $image_extenstion = $images["extension"];
            $image_name_with_ext = $images["basename"];

            $image_name = pathinfo($image_name_with_ext, PATHINFO_FILENAME);
            $image_name = strtolower(Str::slug($image_name));
            $image_db = $image_name . Str::uuid() . '.' . $image_extenstion;

            $folder_path = 'assets/tenant/uploads/media-uploader/'.$subdomain;//todo get tenant subdomain

            if (in_array($image_extenstion,['pdf','doc','docx','txt','svg','zip','csv','xlsx','xlsm','xlsb','xltx','pptx','pptm','ppt'])){
                file_put_contents($folder_path.'/'. $image_db,file_get_contents($image));
            }

            if (in_array($image_extenstion,['jpg','jpeg','png','gif'])){

                self::handle_image_upload(
                    $image_db,
                    $image,
                    $image_name_with_ext,
                    $folder_path,
                    $file
                );
            }

            $admin = Admin::first();

            $imageData = [
                'title' => $image_name_with_ext,
                'size' => null,
                'user_type' => 0, //0 == admin 1 == user
                'path' => $image_db,
                'dimensions' => null,
                'user_id' =>  $admin->id ?? null,
            ];

            $img = MediaUploader::create($imageData);
            return $img->id;
        }

        return null;
    }


    private static function handle_image_upload($image_db, $image, $image_name_with_ext,$folder_path, $file){

        $image_dimension = getimagesize($image);

        $admin = Admin::first();
        $image_width = $image_dimension[0] ?? '';
        $image_height = $image_dimension[1] ?? '';
        $image_dimension_for_db = $image_width . ' x ' . $image_height . ' pixels' ?? null;
        $image_size_for_db = null;
        $image_grid = 'grid-'.$image_db ;
        $image_large = 'large-'. $image_db;
        $image_thumb = 'thumb-'. $image_db;

        $resize_grid_image = Image::make($image)->resize(350, null,function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_large_image = Image::make($image)->resize(740, null,function ($constraint) {
            $constraint->aspectRatio();
        });
        $resize_thumb_image = Image::make($image)->resize(150, 150);


        file_put_contents($folder_path. '/'.$image_db,file_get_contents($image));

        if ($image_width > 150){
            $resize_thumb_image->save($folder_path .'thumb/'. $image_thumb);
            $resize_grid_image->save($folder_path .'grid/'. $image_grid);
            $resize_large_image->save($folder_path .'large/'. $image_large);
        }
    }


}
