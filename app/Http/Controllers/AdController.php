<?php

namespace App\Http\Controllers;

use App\Repositories\AdRepository;
use Illuminate\Http\Request;

class AdController extends Controller
{
    protected $adRepository;

    public function __construct(AdRepository $adRepository)
    {
        $this->middleware('auth:sanctum');
        $this->adRepository = $adRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'region_id', 'price_min', 'price_max', 'search', 'sort_by', 'sort_dir']);
        return response()->json($this->adRepository->all($filters));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'region_id' => 'required|exists:regions,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        $ad = $this->adRepository->create($data);
        if ($request->has('tags')) {
            $ad->tags()->sync($request->tags);
        }

        return response()->json($ad->load(['category', 'region', 'tags']), 201);
    }

    public function show($id)
    {
        return response()->json($this->adRepository->find($id));
    }

    public function update(Request $request, $id)
    {
        $ad = $this->adRepository->find($id);

        if ($ad->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id' => 'exists:categories,id',
            'region_id' => 'exists:regions,id',
            'title' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $ad = $this->adRepository->update($ad, $request->all());
        if ($request->has('tags')) {
            $ad->tags()->sync($request->tags);
        }

        return response()->json($ad->load(['category', 'region', 'tags']));
    }

    public function destroy(Request $request, $id)
    {
        $ad = $this->adRepository->find($id);

        if ($ad->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->adRepository->delete($ad);
        return response()->json(['message' => 'Ad deleted']);
    }
}
