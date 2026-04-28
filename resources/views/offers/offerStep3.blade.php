<x-base-layout>

    <div class="containerOffersIndex">
        <div class="offer-step-stack">
            <div class="offer-steps">
                <div class="offer-step is-complete">
                    <span class="offer-step-dot"></span>
                    <span class="offer-step-label">Stap 1</span>
                </div>
                <span class="offer-step-line is-complete"></span>
                <div class="offer-step is-complete">
                    <span class="offer-step-dot"></span>
                    <span class="offer-step-label">Stap 2</span>
                </div>
                <span class="offer-step-line is-complete"></span>
                <div class="offer-step is-active">
                    <span class="offer-step-dot"></span>
                    <span class="offer-step-label">Stap 3</span>
                </div>
            </div>

            <form method="POST" action="{{ route('offercar.store_tags') }}" class="offer-form">
            <h1>Nieuw aanbod</h1>
            @csrf
            <div class="form-group">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div class="car-plate">
                        <div class="left">NL</div>
                        <span class="number">{{ $car->license_plate }}</span>
                    </div>

                    <div class="back-button">
                        <a href="{{ route('index') }}"
                            style="color:#000; text-decoration:underline; font-size:14px;">Tags overslaan</a>
                    </div>
                </div>
                <input type="hidden" name="car_id" value="{{ $car->id }}">
            </div>
            <div class="form-group full-width">
                <label for="brand">Merk</label>
                <input type="text" id="brand" name="brand" value="{{ $car['make'] ?? '' }}" readonly>
            </div>

            <div class="form-group full-width">
                <label for="model">Model</label>
                <input type="text" id="model" name="model" value="{{ $car['model'] ?? '' }}" readonly>
            </div>

            <div class="form-group full-width">
                <label>Tags (optioneel)</label>
                <div class="tags-container">
                    @foreach($tags as $tag)
                        <label class="tag-checkbox" style="background-color: {{ $tag->color }}; color: white;">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}">
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-actions"
                style="display:flex; gap:12px; align-items:center; justify-content:space-between;">
                <button type="submit" class="btn-submit">Aanbod afzenden</button>

            </div>
            </form>
        </div>
    </div>

</x-base-layout>