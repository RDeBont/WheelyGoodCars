<div>
    <div class="cars-search-bar">
        <input
            type="search"
            class="cars-search-input"
            placeholder="Zoek op merk of model..."
            wire:model.live.debounce.300ms="search"
        >
        <span class="cars-search-hint">{{ $cars->total() }} resultaten</span>
    </div>

    <div class="cars-filter-tags">
        <span class="cars-filter-label">Filter op tags:</span>
        @foreach($tags as $tag)
            <label class="cars-tag-chip" style="border-color: {{ $tag->color }};">
                <input
                    type="checkbox"
                    value="{{ $tag->id }}"
                    wire:model.live="selectedTags"
                >
                <span class="cars-tag-dot" style="background-color: {{ $tag->color }};"></span>
                <span>{{ $tag->name }}</span>
            </label>
        @endforeach
    </div>

    @if($cars->isEmpty())
        <div class="cars-empty">Geen auto's gevonden voor deze zoekterm of filters.</div>
    @else
        <div class="cars-row">
            @foreach($cars as $car)
                <a href="{{ route('cars.show', $car->id) }}" class="car-card card-link{{ in_array($car->id, $featuredIds) ? ' car-card--featured' : '' }}">
                    <div class="car-image">
                        <img src="{{ $car->image ? asset($car->image) : asset('images/default-car.png') }}" alt="{{ $car->make }} {{ $car->model }}">
                    </div>
                    <div class="car-buy">
                        <div class="car-plate">
                            <div class="left">NL</div>
                            <div class="plate">{{ $car->license_plate }}</div>
                        </div>
                        <button class="btn-buy" type="button">Te Koop</button>
                    </div>
                    <h2>{{ $car->make }} {{ $car->model }}</h2>
                    <p><strong>Kilometerstand:</strong> {{ number_format($car->mileage) }} km</p>
                    <p><strong>Prijs:</strong> €{{ number_format($car->price, 2, ',', '.') }}</p>
                    <div class="car-tags">
                        <p><strong>Tags:</strong></p>
                        @foreach($car->tags as $tag)
                            <span class="tag" style="background-color: {{ $tag->color }}; color: white;">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </a>
            @endforeach
        </div>
        <div class="cars-pagination">
            {{ $cars->links() }}
        </div>
    @endif
</div>
