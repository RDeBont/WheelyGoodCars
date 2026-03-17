<x-base-layout>
<div class="owncars-container">
    <div class="owncars-header">
        <h1>Mijn aangeboden auto's</h1>
    </div>

    @if($cars->count() > 0)
        <div class="cars-list">
            @foreach($cars as $car)
            <div class="car-list-item {{ $car->sold_at ? 'is-sold' : '' }}">

                <div class="car-list-image">
                    <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/default-car.png') }}"
                         alt="{{ $car->make }} {{ $car->model }}">
                </div>

                <div class="car-list-info">
                    <div class="car-list-title">
                        <h2>{{ $car->make }} {{ $car->model }}</h2>
                        <div class="plate-small">
                            <span class="nl">NL</span>
                            <span class="number">{{ $car->license_plate }}</span>
                        </div>
                    </div>
                    <div class="car-list-meta">
                        <span>Bouwjaar: {{ $car->production_year }}</span>
                        <span>Kilometerstand: {{ number_format($car->mileage, 0, ',', '.') }} km</span>
                        <span>Geplaatst: {{ $car->created_at->format('d-m-Y') }}</span>
                    </div>
                </div>

                <div class="car-list-right">
                    <livewire:car-status :car="$car" :key="$car->id" />
                    <div class="car-list-actions">
                        <a href="{{ route('cars.pdf', $car->id) }}" class="btn-edit" title="Download PDF" target="_blank">📄</a>
                        <form method="POST" action="{{ route('cars.destroy', $car->id) }}" style="display:inline;"
                              onsubmit="return confirm('Weet je zeker dat je deze auto wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" title="Verwijder">🗑</button>
                        </form>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    @else
        <div class="no-cars-message">
            <p>Je hebt nog geen auto's aangeboden.</p>
        </div>
    @endif
</div>

</x-base-layout>