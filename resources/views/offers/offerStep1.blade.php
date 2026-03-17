<x-base-layout>
    <div class="containerOffersIndex">
        <form method="GET" action="{{ route('offers.addcar') }}" class="bar">
            <div class="left">NL</div>

            <input class="middle" type="text" name="license_plate" placeholder="Zoek hier..."
                style="border:none; outline:none; padding:0 15px;" />

            <button class="right" type="submit">Go!</button>


        </form>
    </div>
</x-base-layout>