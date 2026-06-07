<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::query()
            ->with(['game', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($request->filled('game'), function ($query) use ($request) {
                $query->whereHas('game', fn ($q) => $q->where('slug', $request->game));
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $games = Game::query()
            ->where('is_active', true)
            ->latest()
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->latest()
            ->get();

        return view('frontend.items.index', compact('items', 'games', 'categories'));
    }

    public function show(Item $item)
    {
        abort_if(! $item->is_active || $item->stock < 1, 404);

        $item->load(['game', 'category']);

        $relatedItems = Item::query()
            ->with(['game', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->where('id', '!=', $item->id)
            ->where('game_id', $item->game_id)
            ->latest()
            ->take(4)
            ->get();

        return view('frontend.items.show', compact('item', 'relatedItems'));
    }
}