<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Auto PDF - {{ $car->license_plate }}</title>
    <style>
        body { font-family: Arial, sans-serif; color: #222; }
        .pdf-container { max-width: 700px; margin: 0 auto; padding: 24px; }
        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { font-size: 28px; margin-bottom: 8px; }
        .header .plate {
            display: inline-block;
            background: #ffd700;
            border: 2px solid #333;
            border-radius: 6px;
            font-size: 22px;
            font-weight: bold;
            padding: 6px 18px;
            letter-spacing: 2px;
        }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 24px; }
        .info-table th, .info-table td { padding: 10px 14px; border-bottom: 1px solid #eee; font-size: 16px; }
        .info-table th { background: #f5f5f5; text-align: left; }
        .info-table td { background: #fff; }
        .car-image { text-align: center; margin-bottom: 24px; }
        .car-image img { max-width: 320px; max-height: 180px; border-radius: 8px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="pdf-container">
        <div class="header">
            <h1>Auto aanbieding</h1>
            <div class="plate">NL {{ $car->license_plate }}</div>
        </div>
        <div class="car-image">
            <img src="{{ $car->image ? public_path('storage/' . $car->image) : public_path('images/default-car.png') }}" alt="{{ $car->make }} {{ $car->model }}">
        </div>
        <table class="info-table">
            <tr><th>Merk</th><td>{{ $car->make }}</td></tr>
            <tr><th>Model</th><td>{{ $car->model }}</td></tr>
            <tr><th>Jaar</th><td>{{ $car->production_year }}</td></tr>
            <tr><th>Kleur</th><td>{{ $car->color }}</td></tr>
            <tr><th>Kilometerstand</th><td>{{ number_format($car->mileage, 0, ',', '.') }} km</td></tr>
            <tr><th>Prijs</th><td>€{{ number_format($car->price, 2, ',', '.') }}</td></tr>
            <tr><th>Aantal deuren</th><td>{{ $car->doors }}</td></tr>
            <tr><th>Aantal zitplaatsen</th><td>{{ $car->seats }}</td></tr>
            <tr><th>Geplaatst op</th><td>{{ $car->created_at->format('d-m-Y') }}</td></tr>
            <tr><th>Status</th><td>{{ $car->sold_at ? 'Verkocht' : 'Te koop' }}</td></tr>
        </table>
    </div>
</body>
</html>
