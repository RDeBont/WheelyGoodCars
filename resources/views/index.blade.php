<x-base-layout>
    <div class="home-container">
        <h1>Beschikbare auto's</h1>

        <div class="cars-row">
            @foreach($cars as $car)
                <div class="car-card">
                    <div class="car-image">
                        <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/default-car.png') }}" alt="{{ $car->make }} {{ $car->model }}">
                    </div>
                    <div class="car-buy">
                        <div class="car-plate">
                            <div class="left">NL</div>
                            <div class="plate">{{ $car->license_plate }}</div>
                        </div>
                        <button class="btn-buy">Te Koop </button>
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
                </div>
            @endforeach
        </div>

    
    </div>


    </style>
</x-base-layout>