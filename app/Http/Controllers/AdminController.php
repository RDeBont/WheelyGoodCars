<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tag;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin) {
            abort(403);
        }

        $tags = Tag::query()
            ->withCount('cars')
            ->withCount([
                'cars as sold_cars_count' => fn($q) => $q->whereNotNull('sold_at'),
                'cars as unsold_cars_count' => fn($q) => $q->whereNull('sold_at'),
            ])
            ->orderBy('name')
            ->get();

        return view('admin.index', compact('tags'));
    }


}
