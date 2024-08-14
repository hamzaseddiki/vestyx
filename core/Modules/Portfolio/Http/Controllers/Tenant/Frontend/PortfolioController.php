<?php

namespace Modules\Portfolio\Http\Controllers\Tenant\Frontend;
use Illuminate\Routing\Controller;
use Modules\Donation\Helpers\DataTableHelpers\EventGeneral;
use Modules\Portfolio\Entities\Portfolio;
use Modules\Portfolio\Entities\PortfolioCategory;


class PortfolioController extends Controller
{
    //private const BASE_PATH = 'portfolio::tenant.frontend.portfolio.';
    private const BASE_PATH = 'portfolio.';

    public function portfolio_details($slug)
    {
        abort_if(empty($slug),404);
        $portfolio = Portfolio::with('category')->where('slug',$slug)->first();
        $more_portfolios = Portfolio::select('id','slug','image')->orderBy('id','desc')->take(5)->get();
        $categories = PortfolioCategory::select('id','title')->where('status',1)->get();
        return themeView(self::BASE_PATH.'portfolio-details',compact('portfolio','categories','more_portfolios'));
    }

    public function category_wise_portfolio($id,$slug)
    {
        abort_if(empty($slug),404);
        $portfolio = Portfolio::with('category')->where('category_id',$id)->paginate(8);
        return themeView(self::BASE_PATH.'portfolio-category',compact('portfolio','slug'));
    }


}
