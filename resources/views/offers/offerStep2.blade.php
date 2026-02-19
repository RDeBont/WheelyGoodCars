<x-base-layout>

    <div class="containerOffersIndex">

        
        <form method="POST" action="{{ route('offercar.store') }}" class="offer-form">
            <h1>Nieuw aanbod</h1>
            @csrf

            <div class="form-group full-width">
                <div class="license-plate-display">
                    <div class="left">NL</div>
                    <input type="text" id="license_plate" name="license_plate" value="{{ strtoupper($license_plate) }}" disabled>
                </div>
                <input type="hidden" name="license_plate" value="{{ strtoupper($license_plate) }}">
            </div>

            <div class="form-group full-width">
                <label for="brand">Merk</label>
                <input type="text" id="brand" name="brand" value="{{ $car_data['merk'] ?? '' }}" readonly>
            </div>

            <div class="form-group full-width">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" value="{{ $car_data['handelsbenaming'] ?? '' }}" readonly>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label for="seats">Aantal zitplaatsen</label>
                    <input type="number" id="seats" name="seats" value="{{ $car_data['aantal_zitplaatsen'] ?? '' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="doors">Aantal deuren</label>
                    <input type="number" id="doors" name="doors" value="{{ $car_data['aantal_deuren'] ?? '' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="mass_ready">Massa rijklaar</label>
                    <input type="number" id="mass_ready" name="mass_ready" value="{{ $car_data['massa_rijklaar'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label for="year">Jaar van productie</label>
                    <input type="number" id="year" name="year" value="{{ $car_data['datum_eerste_toelating'] ? substr($car_data['datum_eerste_toelating'], 0, 4) : '' }}" readonly>
                </div>

                <div class="form-group">
                    <label for="color_primary">Kleur</label>
                    <input type="text" id="color_primary" name="color_primary" value="{{ $car_data['eerste_kleur'] ?? '' }}" readonly>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="kilometers">Kilometerstand</label>
                <div class="input-with-unit">
                    <input type="number" id="kilometers" name="kilometers" placeholder="Voer kilometerstand in" required>
                    <span class="unit">km</span>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="price">Vraagprijs</label>
                <div class="input-with-unit">
                    <span class="currency">€</span>
                    <input type="number" id="price" name="price" placeholder="Voer verkoopprijs in" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">Aanbod afzenden</button>
        </form>
    </div>

</x-base-layout>