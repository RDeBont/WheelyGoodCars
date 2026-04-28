<x-base-layout>
<div class="owncars-container">

    <div style="margin-bottom:20px;">
        <a href="{{ url()->previous() ?: route('index') }}" class="btn-details">← Terug</a>
    </div>

    <div class="car-detail-card">

        <!-- Links: Foto + Kenteken -->
        <div class="car-detail-left">

            @if($car->image)
                <img src="{{ asset($car->image) }}"
                     alt="{{ $car->make }} {{ $car->model }}"
                     class="car-detail-img">
            @else
                <div class="car-list-image-placeholder car-detail-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="#ccc" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                    </svg>
                    <span>Geen foto beschikbaar</span>
                </div>
            @endif

            <!-- Kenteken -->
            <div class="plate-large">
                <div class="plate-large-nl">🇳🇱</div>
                <div class="plate-large-number">{{ strtoupper($car->license_plate) }}</div>
            </div>
        </div>

        <!-- Rechts: Info -->
        <div class="car-detail-right">
            <h1 class="car-detail-title">{{ $car->make }} {{ $car->model }}</h1>
            <p class="car-detail-year">{{ $car->production_year }}</p>

            <div class="car-tags">
                @foreach($car->tags as $tag)
                    <span class="tag" style="background-color:{{ $tag->color }}">{{ $tag->name }}</span>
                @endforeach
            </div>

            <table class="cars-table" style="margin-top:16px;">
                @php
                    $rows = [
                        ['Prijs', '€' . number_format($car->price, 2, ',', '.')],
                        ['Kilometerstand', number_format($car->mileage, 0, ',', '.') . ' km'],
                        ['Kleur', $car->color],
                        ['Stoelen', $car->seats],
                        ['Deuren', $car->doors],
                        ['Gewicht', $car->weight ? $car->weight . ' kg' : '—'],
                    ];
                @endphp
                @foreach($rows as [$label, $value])
                    <tr>
                        <td style="color:#888; width:45%;">{{ $label }}</td>
                        <td class="price">{{ $value }}</td>
                    </tr>
                @endforeach
            </table>

            <div style="margin-top:24px; display:flex; gap:10px;">
                @auth
                    @if(auth()->id() === $car->user_id)
                        <a href="{{ route('owncars') }}" class="btn-details">Mijn aanbod beheren</a>
                    @else
                        <a href="{{ route('index') }}" class="btn-details">Terug naar overzicht</a>
                    @endif
                @else
                    <a href="{{ route('index') }}" class="btn-details">Terug naar overzicht</a>
                @endauth
            </div>
        </div>

    </div>
</div>
@php
    $viewCount = $car->views ?? 10;
@endphp

<!-- TOAST -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="car-views-toast"
         class="toast"
         role="status"
         aria-live="polite"
         aria-atomic="true"
        data-bs-autohide="false">
        <div class="toast-header">
            <strong class="me-auto">Populair vandaag</strong>
            <small>Aantal keer bekken</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>

        <div class="toast-body">
            {{ $viewCount }} klanten bekeken deze auto vandaag
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        const toastEl = document.getElementById('car-views-toast');
        if (!toastEl) return;

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }, 10000); // 10 seconden delay
});
</script>
</x-base-layout>