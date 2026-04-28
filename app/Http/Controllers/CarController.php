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
use App\Models\Tag;
use App\Models\Car_tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Offer;


class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cars = Car::with('tags')->get();
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
        return view('offers.offerStep1');
    }

    public function create_step1()
    {


        $license_plate_api = strtoupper(str_replace('-', '', request('license_plate')));
        $license_plate = strtoupper(request('license_plate'));

       if (Car::where('license_plate', $license_plate)->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'license_plate' => 'Er is al een aanbod met dit kenteken.'
                ]);
        }


        
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
            'license_plate' => $license_plate,
        ]);
    }

    public function create_step2()
    {
        $tags = Tag::all();

        $car_data = session('car_api_data');
        $license_plate = request('license_plate');

        if (!$car_data) {
            return redirect()->route('offers.offerStep1');
        }

        return view('offers.offerStep2', [
            'license_plate' => $license_plate,
            'car_data' => $car_data,
            'tags' => $tags,
        ]);

        

    }

    public function create_step3()
    {
        
        $tags = Tag::all();
        $car_data = session('car_api_data');
        $license_plate = request('license_plate');
        $car_id = request('car_id');

        if (!$car_data) {
            return redirect()->route('offers.offerStep1');
        }

        return view('offers.offerStep3', [
            'tags' => $tags,

            'license_plate' => $license_plate,
            'car_id' => $car_id,
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
            'img' => 'nullable|image|max:2048',
        ],[
            'license_plate.required' => 'Kenteken is verplicht.',
            'kilometers.required' => 'Kilometers zijn verplicht.',
            'kilometers.numeric' => 'Kilometers moeten een getal zijn.',
            'kilometers.min' => 'Kilometers moeten minimaal 0 zijn.',
            'price.required' => 'Prijs is verplicht.',
            'price.numeric' => 'Prijs moet een getal zijn.',
            'price.min' => 'Prijs moet minimaal 0 zijn.',
            'img.image' => 'Het bestand moet een afbeelding zijn.',
            'img.max' => 'De afbeelding mag niet groter zijn dan 2MB.',

        ]);

        $img = null;
        $imageFile = $request->file('img');
        if ($imageFile && !$imageFile->isValid()) {
            return back()
                ->withInput()
                ->withErrors([
                    'image' => 'Upload mislukt. Controleer bestandstype en grootte.'
                ]);
        }
        if ($imageFile && $imageFile->isValid()) {
            $targetDir = public_path('img/cars');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $extension = $imageFile->getClientOriginalExtension();
            $filename = uniqid('car_', true) . '.' . $extension;
            $imageFile->move($targetDir, $filename);
            $img = '/img/cars/' . $filename;
        }

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
            'image' => $img,
        ]);

        session()->forget('car_api_data');
        $tags = Tag::all();
        $car = Car::where('license_plate', $validated['license_plate'])->first();
        return redirect()->route('offercar.step3', ['car' => $car])->with('tags', $tags)->with('success', 'Auto aanbod succesvol Opgeslagen!');
    }

    
    public function create_step4(Car $car)
    {
        
        $tags = Tag::orderBy('name')->get();
        return view('offers.offerStep3', compact('car', 'tags'));
    }

    public function store_tags(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ],[
            'car_id.required' => 'Er is een fout opgetreden bij het opslaan van de tags. Probeer het opnieuw.',
            'car_id.exists' => 'De opgegeven auto bestaat niet.',
            'tags.required' => 'Selecteer minimaal één tag, of klik op "Opslaan zonder tags".',
            'tags.array' => 'Ongeldige tags-indeling.',
            'tags.*.exists' => 'Een of meer geselecteerde tags bestaan niet.',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        $car->tags()->sync($validated['tags']);

        return redirect()->route('owncars')->with('success', 'Auto en/of tags succesvol opgeslagen!');
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Car $car)
    {
        try {
            $car->increment('views');
        } catch (\Exception $e) {
        }

        return view('cars.show', compact('car'));
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

    public function editTags(Car $car): View
    {
        if ($car->user_id !== auth()->id()) {
            return redirect()->route('owncars')->withErrors([
                'error' => 'Je hebt geen toestemming om deze tags te bewerken.'
            ]);
        }

        $tags = Tag::orderBy('name')->get();
        $selectedTagIds = $car->tags()->pluck('tags.id')->all();

        return view('own.edit-tags', compact('car', 'tags', 'selectedTagIds'));
    }

    public function updateTags(Request $request, Car $car): RedirectResponse
    {
        if ($car->user_id !== auth()->id()) {
            return back()->withErrors([
                'error' => 'Je hebt geen toestemming om deze tags te bewerken.'
            ]);
        }

        $validated = $request->validate([
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ], [
            'tags.array' => 'Ongeldige tags-indeling.',
            'tags.*.exists' => 'Een of meer geselecteerde tags bestaan niet.',
        ]);

        $car->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('owncars')->with('success', 'Tags bijgewerkt!');
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
        $pdf = Pdf::loadView('own.car_pdf', [
            'car' => $car,
            'user' => $car->user,
        ]);
        return $pdf->download('auto-' . $car->license_plate . '.pdf');
    }
}
