<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Game;
use App\Models\Item;

class HomeController extends Controller
{
    public function index()
    {
        $games = Game::query()
            ->where('is_active', true)
            ->withCount([
                'items' => fn ($query) => $query
                    ->where('is_active', true)
                    ->where('stock', '>', 0),
            ])
            ->latest()
            ->take(6)
            ->get();

        $categories = Category::query()
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $featuredItems = Item::query()
            ->with(['game', 'category'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $promoItems = Item::query()
            ->with(['game', 'category'])
            ->where('is_active', true)
            ->where('is_promo', true)
            ->where('stock', '>', 0)
            ->where(function ($query) {
                $query->whereNull('promo_ends_at')
                    ->orWhere('promo_ends_at', '>=', now());
            })
            ->latest()
            ->take(4)
            ->get();

        $latestItems = Item::query()
            ->with(['game', 'category'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.home', compact(
            'games',
            'categories',
            'featuredItems',
            'promoItems',
            'latestItems'
        ));
    }
}