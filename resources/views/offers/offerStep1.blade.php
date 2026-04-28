<x-base-layout>
    <div class="containerOffersIndex">
        <div class="offer-step-stack">
            <div class="offer-steps">
                <div class="offer-step is-active">
                    <span class="offer-step-dot"></span>
                    <span class="offer-step-label">Stap 1</span>
                </div>
                <span class="offer-step-line"></span>
                <div class="offer-step">
                    <span class="offer-step-dot"></span>
                    <span class="offer-step-label">Stap 2</span>
                </div>
                <span class="offer-step-line"></span>
                <div class="offer-step">
                    <span class="offer-step-dot"></span>
                    <span class="offer-step-label">Stap 3</span>
                </div>
            </div>
            <form method="GET" action="{{ route('offers.addcar') }}" class="bar">
                <div class="left">NL</div>

                <input class="middle" type="text" name="license_plate" placeholder="Zoek hier..."
                    style="border:none; outline:none; padding:0 15px;" />

                <button class="right" type="submit">Go!</button>
            </form>
        </div>
    </div>
</x-base-layout>