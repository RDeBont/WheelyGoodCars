<?php

namespace App\Livewire;

use App\Models\Car;
use Livewire\Component;
use Livewire\WithPagination;

class CarSearch extends Component
{
    use WithPagination;

    public string $search = '';

    protected string $paginationTheme = 'bootstrap';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

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

        $cars = $query->paginate(13);
        $featuredCount = max(1, (int) ceil($cars->count() / 6));
        $featuredIds = $cars->getCollection()->pluck('id')->shuffle()->take($featuredCount)->all();

        return view('livewire.car-search', [
            'cars' => $cars,
            'featuredIds' => $featuredIds,
        ]);
    }
}
