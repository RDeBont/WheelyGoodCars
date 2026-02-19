<x-base-layout>
    <div class="owncars-container">
        <div class="owncars-header">
            <h1>Mijn aangeboden auto's</h1>
        </div>

        @if($cars->count() > 0)
            <div class="table-responsive">
                <table class="cars-table">
                    <thead>
                        <tr>
                            <th>foto</th>
                            <th>Kenteken</th>
                            <th>Merk & Model</th>
                            <th>Jaar</th>
                            <th>Kilometerstand</th>
                            <th>Prijs</th>
                            <th>Status</th>
                            <th>Geplaatst op</th>
                            <th>Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cars as $car)
                            <tr>
                                <td>
                                    <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('images/default-car.png') }}" alt="{{ $car->make }} {{ $car->model }}" class="car-thumbnail">
                                </td>
                                <td>
                                    <div class="plate-small">
                                        <span class="nl">NL</span>
                                        <span class="number">{{ $car->license_plate }}</span>
                                    </div>
                                </td>
                                <td><strong>{{ $car->make }} {{ $car->model }}</strong></td>
                                <td>{{ $car->production_year }}</td>
                                <td>{{ number_format($car->mileage, 0, ',', '.') }} km</td>
                                <td class="price">€{{ number_format($car->price, 2, ',', '.') }}</td>
                                <td>
                                    <span class="status {{ $car->sold_at ? 'sold' : 'active' }}">
                                        {{ $car->sold_at ? 'Verkocht' : 'Te koop' }}
                                    </span>
                                </td>
                                <td>{{ $car->created_at->format('d-m-Y') }}</td>
                                <td class="actions">
                                    <a href="{{ route('cars.pdf', $car->id) }}" class="btn-edit" title="Download PDF" target="_blank">📄</a>

                                    <a href="#" class="btn-edit" title="Bewerk">✎</a>
                                    <form method="POST" action="{{ route('cars.destroy', $car->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete" title="Verwijder">🗑</button>
                                    </form>                                   

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="no-cars-message">
                <p>Je hebt nog geen auto's aangeboden.</p>
            </div>
        @endif
    </div>
</x-base-layout>