<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Tag;
use App\Models\User;
use Carbon\Carbon;

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
            ->having('cars_count', '>', 0)
            ->orderBy('name')
            ->get();

        $suspiciousUsers = User::query()
            ->where('is_admin', 0)
            ->withCount('cars')
            ->with('cars.tags')
            ->get()
            ->map(function ($user) {
                $flags = [];

                if (empty($user->phone)) {
                    $flags[] = 'Geen telefoonnummer';
                }

                $suspiciousCars = $user->cars->filter(
                    fn($car) =>
                    (date('Y') - $car->production_year) > 10 && $car->mileage < 10000
                );
                if ($suspiciousCars->count() > 0) {
                    $flags[] = 'Oude auto met lage km (' . $suspiciousCars->count() . 'x)';
                }

                $sameDaySold = $user->cars->filter(
                    fn($car) =>
                    $car->sold_at &&
                    $car->price > 10000 &&
                    Carbon::parse($car->created_at)->isSameDay($car->sold_at)
                );
                if ($sameDaySold->count() > 3) {
                    $flags[] = 'Meer dan 3 auto\'s direct verkocht boven €10.000 (' . $sameDaySold->count() . 'x)';
                }

                if ($user->cars->count() > 0 && $user->cars->every(fn($car) => $car->price < 1000)) {
                    $flags[] = 'Alle auto\'s onder €1.000';
                }

                $carsWithTags = $user->cars->filter(fn($car) => $car->tags->count() > 0);
                if ($user->cars->count() > 0 && $carsWithTags->count() === 0) {
                    $flags[] = 'Geen tags gebruikt';
                }

                $lastCar = $user->cars->sortByDesc('created_at')->first();
                if ($lastCar && Carbon::parse($lastCar->created_at)->lt(now()->subYear())) {
                    $flags[] = 'Geen nieuwe auto\'s in het laatste jaar';
                }

                $user->flags = $flags;
                return $user;
            })
            ->filter(fn($user) => count($user->flags) > 0)
            ->sortByDesc(fn($user) => count($user->flags))
            ->values();

        return view('admin.index', compact('tags', 'suspiciousUsers'));
    }

    public function dashboardData()
    {
        $user = auth()->user();
        if (!$user || !$user->is_admin)
            abort(403);

        $viewsToday = Car::whereDate('updated_at', today())->sum('views');

        return response()->json([
            'totalCars' => Car::count(),
            'soldCars' => Car::whereNotNull('sold_at')->count(),
            'availableCars' => Car::whereNull('sold_at')->count(),
            'todayCars' => Car::whereDate('created_at', today())->count(),
            'totalSellers' => User::where('is_admin', 0)->count(),
            'avgCarsPerSeller' => User::where('is_admin', 0)->count() > 0
                ? round(Car::count() / User::where('is_admin', 0)->count(), 1) : 0,
            'soldPercent' => Car::count() > 0
                ? round(Car::whereNotNull('sold_at')->count() / Car::count() * 100) : 0,
            'carsByMake' => Car::selectRaw('make, COUNT(*) as count')->groupBy('make')
                ->orderByDesc('count')->limit(8)->get()
                ->map(fn($r) => ['label' => $r->make, 'y' => $r->count]),
            'soldPerDay' => Car::selectRaw('DATE(sold_at) as date, COUNT(*) as count')
                ->whereNotNull('sold_at')->where('sold_at', '>=', now()->subDays(14))
                ->groupBy('date')->orderBy('date')->get()
                ->map(fn($r) => ['x' => $r->date, 'y' => $r->count]),
            'topTags' => Tag::withCount('cars')->having('cars_count', '>', 0)
                ->orderByDesc('cars_count')->limit(8)->get()
                ->map(fn($t) => ['label' => $t->name, 'y' => $t->cars_count, 'color' => $t->color]),
            'updatedAt' => now()->format('H:i:s'),
            'viewsToday' => $viewsToday,
        ]);
    }
}