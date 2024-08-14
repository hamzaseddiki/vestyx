<?php

namespace Modules\Attributes\Http\Controllers;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use Modules\Attributes\Entities\Tag;
use Modules\Attributes\Http\Requests\UpdateTagRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TagController extends Controller
{
    private const BASE_PATH = 'attributes::backend.';
    // product-tag-list
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:product-tag-list|product-tag-create|product-tag-edit|product-tag-delete', ['only', ['index']]);
        $this->middleware('permission:product-tag-create', ['only', ['store']]);
        $this->middleware('permission:product-tag-edit', ['only', ['update']]);
        $this->middleware('permission:product-tag-delete', ['only', ['destroy', 'bulk_action']]);
    }

    /**
     * Display a listing of the resource.
     * @return Factory|View|Application
     */
    public function index(Request $request): Factory|View|Application
    {
        $all_tag = Tag::all();
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        return view(self::BASE_PATH.'tag.all-tag', compact('all_tag','default_lang'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate(['title' => 'required|string|max:191|unique:tags,tag_text']);
        $tag = new Tag();
        $tag->setTranslation('tag_text',$request->lang,$request->title);
        $tag->save();

        return $tag->id
            ? back()->with(FlashMsg::create_succeed(__('Tag')))
            : back()->with(FlashMsg::create_failed(__('Tag')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTagRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateTagRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $tag = Tag::find($request->id);
        $tag->setTranslation('tag_text',$request->lang,$data['tag_text']);
        $tag->save();


        return $tag
            ? back()->with(FlashMsg::update_succeed(__('Tag')))
            : back()->with(FlashMsg::update_failed(__('Tag')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tag $item
     * @return RedirectResponse
     */
    public function destroy(Tag $item): RedirectResponse
    {
        return $item->delete()
            ? back()->with(FlashMsg::delete_succeed(__('Tag')))
            : back()->with(FlashMsg::delete_failed(__('Tag')));
    }

    public function bulk_action(Request $request): JsonResponse
    {
        Tag::WhereIn('id', $request->ids)->delete();

        return response()->json(['status' => 'ok']);
    }

    public function getTagsAjax(Request $request): JsonResponse
    {
        $request->validate(['tag_query' => 'nullable|string|max:191']);
        $tags = Tag::select('id', 'tag_text as tag')->where('tag_text', 'LIKE', "%". filter_value_for_query($request->tag_query) ."%")->get();

        return response()->json(['result' => $tags], 200);
    }
}
