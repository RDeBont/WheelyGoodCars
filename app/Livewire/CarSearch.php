<?php

namespace App\Livewire;

use App\Models\Car;
use Livewire\Component;

class CarSearch extends Component
{
    public string $search = '';

    public function render()
    {
        $query = Car::query()->with('tags');

        if ($this->search !== '') {
            $term = '%' . $this->search . '%';
            $query->where(function ($builder) use ($term) {
                $builder->where('make', 'like', $term)
                    ->orWhere('model', 'like', $term);
            });
        }

        $cars = $query->get();
        $featuredCount = max(1, (int) ceil($cars->count() / 6));
        $featuredIds = $cars->pluck('id')->shuffle()->take($featuredCount)->all();

        return view('livewire.car-search', [
            'cars' => $cars,
            'featuredIds' => $featuredIds,
        ]);
    }
}
