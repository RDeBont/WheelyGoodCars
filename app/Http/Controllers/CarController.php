<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::all();
        return view('index', compact('cars'));
    }

    /**
     * Show user's own cars
     */
    public function owncars()
    {
        $cars = Car::where('user_id', auth()->id())->get();
        return view('own.index', compact('cars'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function create_step1()
    {


        $license_plate_api = strtoupper(str_replace('-', '', request('license_plate')));
        $license_plate = strtoupper(request('license_plate'));

       


        
        $response = Http::withHeaders([
            'X-App-Token' => config('services.rdw.token'),
            'Accept' => 'application/json',
        ])->get('https://opendata.rdw.nl/resource/m9d7-ebf2.json', [
            'kenteken' => $license_plate_api,
        ]);


       

       if ($response->failed()) {
            return back()
                ->withInput()
                ->withErrors([
                    'license_plate' => 'Fout bij het ophalen van gegevens. Probeer het later opnieuw.'
                ]);
        }

        $data = $response->json();

        if (empty($data)) {
            return back()
                ->withInput()
                ->withErrors([
                    'license_plate' => 'Kenteken niet gevonden'
                ]);
        }

   
        session(['car_api_data' => $data[0]]);

        return redirect()->route('offercar.step2', [
            'license_plate' => $license_plate
        ]);
    }

    public function create_step2()
    {
        $car_data = session('car_api_data');
        $license_plate = request('license_plate');

        if (!$car_data) {
            return redirect()->route('offers.offerStep1');
        }

        return view('offers.offerStep2', [
            'license_plate' => $license_plate,
            'car_data' => $car_data
        ]);


    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'license_plate' => 'required|string',
            'kilometers' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ],[
            'license_plate.required' => 'Kenteken is verplicht.',
            'kilometers.required' => 'Kilometers zijn verplicht.',
            'kilometers.numeric' => 'Kilometers moeten een getal zijn.',
            'kilometers.min' => 'Kilometers moeten minimaal 0 zijn.',
            'price.required' => 'Prijs is verplicht.',
            'price.numeric' => 'Prijs moet een getal zijn.',
            'price.min' => 'Prijs moet minimaal 0 zijn.',
        ]);

        $car_data = session('car_api_data');

        if (!$car_data) {
            return back()->withErrors(['error' => 'Sessiegegevens verloren gegaan.']);
        }

        $car = Car::create([
            'user_id' => auth()->id(),
            'license_plate' => $validated['license_plate'],
            'make' => $car_data['merk'] ?? null,
            'model' => $car_data['handelsbenaming'] ?? null,
            'price' => $validated['price'],
            'mileage' => $validated['kilometers'],
            'seats' => $car_data['aantal_zitplaatsen'] ?? null,
            'doors' => $car_data['aantal_deuren'] ?? null,
            'production_year' => $car_data['datum_eerste_toelating'] ? substr($car_data['datum_eerste_toelating'], 0, 4) : null,
            'weight' => $car_data['massa_rijklaar'] ?? null,
            'color' => $car_data['eerste_kleur'] ?? null,
        ]);

        session()->forget('car_api_data');

        return redirect()->route('index')->with('success', 'Auto aanbod succesvol aangemaakt!');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Car $car)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCarRequest $request, Car $car)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $car=Car::findOrFail($id);
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors(['error' => 'Je hebt geen toestemming om deze auto te verwijderen.']);
        }

        $car->delete();

        return redirect()->route('owncars')->with('success', 'Auto succesvol verwijderd!');
    }

    /**
     * Export a single car as PDF
     */
    public function exportPdf(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }
        $pdf = Pdf::loadView('own.car_pdf', compact('car'));
        return $pdf->download('auto-' . $car->license_plate . '.pdf');
    }
}
