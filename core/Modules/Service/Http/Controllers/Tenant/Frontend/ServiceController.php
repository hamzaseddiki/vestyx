<?php

namespace Modules\Service\Http\Controllers\Tenant\Frontend;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Modules\Blog\Entities\Blog;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;


class ServiceController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
    //private const BASE_PATH = 'service::tenant.frontend.service.';
    private const BASE_PATH = 'service.';

    public function service_single($slug)
    {
        $service = Service::where(['slug'=> $slug, 'status' => 1])->first();
        $this->setMetaDataInfo($service);
        $all_related_services = Service::select('id','title','description','image','slug')->orderBy('id','desc')->take(2)->get();

        if(empty($service)){
            abort(404);
        }
        return themeView(self::BASE_PATH.'service-single',compact('service','all_related_services'));
    }

    public function service_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_services = Service::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(4);

        return themeView(self::BASE_PATH.'service-search')->with([
            'all_services' => $all_services,
            'search_term' => $request->search,
        ]);
    }

    public function category_wise_service_page($id)
    {

        if(empty($id)){
            abort(404);
        }
        $all_services = Service::usingLocale(GlobalLanguage::default_slug())->where(['category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(4);
        $category = ServiceCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return themeView(self::BASE_PATH.'service-category')->with([
            'all_services' => $all_services,
            'category_name' => $category_name,
        ]);
    }


}
