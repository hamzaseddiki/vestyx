<?php

namespace Modules\Badge\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Routing\Controller;
use Modules\Badge\Entities\Badge;

class BadgeController extends Controller
{
    private const BASE_PATH = 'badge::tenant.admin.badge.';

    public function index(Request $request)
    {
        $badges = Badge::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'index', compact('badges','default_lang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sale_count' => 'nullable|numeric',
            'badge_type' => 'nullable',
            'status' => 'required',
            'image' => 'required|numeric'
        ],
        [
            'image.required' => 'The badge image is required'
        ]);

        $badge = new Badge();
        $badge->setTranslation('name',$request->lang,$request->name);
        $badge->status = $request->status;
        $badge->image = $request->image;
        $badge->save();

        return $badge->id
            ? back()->with(FlashMsg::create_succeed('Badge'))
            : back()->with(FlashMsg::create_failed('Badge'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {

    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'sale_count' => 'nullable|numeric',
            'badge_type' => 'nullable',
            'status' => 'required',
            'image' => 'required|numeric'
        ],
            [
                'image.required' => 'The badge image is required'
            ]);

        $badge = Badge::findOrFail($id);
        $badge->setTranslation('name',$request->lang,$request->name);
        $badge->status = $request->status;
        $badge->image = $request->image;
        $badge->save();

        return $badge->id
            ? back()->with(FlashMsg::update_succeed('Badge'))
            : back()->with(FlashMsg::update_failed('Badge'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $deleted = Badge::findOrFail($id)->delete();

        return $deleted ? back()->with(FlashMsg::delete_succeed('Badge')) : back()->with(FlashMsg::delete_failed('Badge'));
    }

    public function bulk_action_delete(Request $request): JsonResponse
    {
        $deleted = Badge::whereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function trash()
    {
        $badges = Badge::onlyTrashed()->get();
        return view(self::BASE_PATH.'trash', compact('badges'));
    }


    public function trash_restore($id)
    {
        $restored = Badge::withTrashed()->findOrFail($id)->restore();

        return $restored
            ? back()->with(FlashMsg::restore_succeed('Badge'))
            : back()->with(FlashMsg::restore_failed('Badge'));
    }

    public function trash_delete($id)
    {
        $deleted = Badge::withTrashed()->findOrFail($id)->forceDelete();

        return $deleted
            ? back()->with(FlashMsg::delete_succeed('Badge'))
            : back()->with(FlashMsg::delete_failed('Badge'));
    }

    public function trash_bulk_action_delete(Request $request): JsonResponse
    {
        $deleted = Badge::withTrashed()->whereIn('id', $request->ids)->forceDelete();

        return response()->json(['status' => 'ok']);
    }
}
