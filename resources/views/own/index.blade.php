<x-base-layout>
    <div class="owncars-container">

        <div class="owncars-header">
            <h1>Mijn aangeboden auto's</h1>
        </div>

        @if($cars->count() > 0)
            <div class="cars-list">
                @foreach($cars as $car)
                    <div class="car-list-item {{ $car->sold_at ? 'is-sold' : '' }}">

                        <!-- Foto -->
                        <div class="car-list-image">
                            <a href="{{ route('cars.show', $car->id) }}">
                                @if($car->image)
                                    <img src="{{ asset($car->image) }}" alt="{{ $car->make }} {{ $car->model }}">
                                @else
                                    <div class="car-list-image-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none"
                                            viewBox="0 0 24 24" stroke="#ccc" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                        </svg>
                                        <span>Geen foto</span>
                                    </div>
                                @endif
                            </a>
                        </div>

                        <!-- Info -->
                        <div class="car-list-info">
                            <div class="car-list-title">
                                <h2>{{ $car->make }} {{ $car->model }}</h2>
                                <div class="plate-small">
                                    <span class="nl">🇳🇱</span>
                                    <span class="number">{{ strtoupper($car->license_plate) }}</span>
                                </div>
                            </div>

                            <div class="car-list-meta">
                                <span> {{ $car->production_year }}</span>
                                <span> {{ number_format($car->mileage, 0, ',', '.') }} km</span>
                                <span> {{ $car->created_at->format('d-m-Y') }}</span>
                                <span> <strong>{{ $car->views ?? 0 }}</strong> views</span>
                            </div>

                            <div class="car-tags">
                                @foreach($car->tags as $tag)
                                    <span class="tag" style="background-color:{{ $tag->color }}">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Rechts: status + acties -->
                        <div class="car-list-right">
                            <livewire:car-status :car="$car" :key="$car->id" />
                            <div class="car-list-actions">
                                <a href="{{ route('owncars.tags.edit', $car->id) }}" class="btn-edit" title="Tags bewerken">🏷️</a>
                                <a href="{{ route('cars.pdf', $car->id) }}" class="btn-edit" title="Download PDF"
                                    target="_blank">📄</a>
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