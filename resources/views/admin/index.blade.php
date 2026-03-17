<x-base-layout>
    <div class="admin-page">

        <!-- Sticky nav buttons -->
        <div class="admin-nav">
            <button onclick="scrollToSection('dashboard')" class="admin-nav-btn active" id="btn-dashboard">
                Dashboard
            </button>
            <button onclick="scrollToSection('tags')" class="admin-nav-btn" id="btn-tags">
                Tags
            </button>
            <button onclick="scrollToSection('aanbieders')" class="admin-nav-btn" id="btn-aanbieders">
                Aanbieders
            </button>
        </div>

        <!-- Dashboard -->
        <section id="dashboard" class="admin-section">
            <div class="section-header">
                <h1>Live Dashboard</h1>
                <span class="update-badge">Bijgewerkt: <strong id="lastUpdate">laden...</strong></span>
            </div>

            <div class="stat-grid">
                <div class="stat-card">
                    <span class="stat-label">Totaal aangeboden</span>
                    <span class="stat-value blue" id="totalCars">–</span>
                    <span class="stat-sub">auto's in systeem</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Verkocht</span>
                    <span class="stat-value green" id="soldCars">–</span>
                    <span class="stat-sub">afgeronde verkopen</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Beschikbaar</span>
                    <span class="stat-value yellow" id="availableCars">–</span>
                    <span class="stat-sub">nog te koop</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Vandaag toegevoegd</span>
                    <span class="stat-value teal" id="todayCars">–</span>
                    <span class="stat-sub">nieuwe aanbiedingen</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Aanbieders</span>
                    <span class="stat-value purple" id="totalSellers">–</span>
                    <span class="stat-sub">geregistreerd</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Gem. per aanbieder</span>
                    <span class="stat-value red" id="avgCars">–</span>
                    <span class="stat-sub">auto's gemiddeld</span>
                </div>
                <div class="stat-card">
                    <span class="stat-label">Views vandaag</span>
                    <span class="stat-value teal" id="viewsToday">–</span>
                    <span class="stat-sub">bekeken vandaag</span>
                </div>
            </div>

            <div class="admin-card" style="margin-bottom:24px;">
                <div class="progress-header">
                    <strong>Verkoopvoortgang</strong>
                    <strong><span id="soldPercent">0</span>% verkocht</strong>
                </div>
                <div class="progress-track">
                    <div id="progressBar" class="progress-fill">
                        <span id="progressLabel"></span>
                    </div>
                </div>
            </div>

            <div class="chart-grid-2">
                <div class="admin-card">
                    <h3 class="chart-title">Auto's per merk</h3>
                    <div id="makeChart" style="height:280px;"></div>
                </div>
                <div class="admin-card">
                    <h3 class="chart-title">Populaire tags</h3>
                    <div id="tagChart" style="height:280px;"></div>
                </div>
            </div>

            <div class="chart-grid-3">
                <div class="admin-card" style="grid-column: span 2;">
                    <h3 class="chart-title">Verkopen afgelopen 14 dagen</h3>
                    <div id="lineChart" style="height:280px;"></div>
                </div>
                <div class="admin-card">
                    <h3 class="chart-title">Verkocht vs Beschikbaar</h3>
                    <div id="pieChart" style="height:280px;"></div>
                </div>
            </div>
        </section>

        <!-- SECTIE 2: Tags -->
        <section id="tags" class="admin-section">
            <div class="section-header">
                <h1>Tag overzicht</h1>
            </div>
            <div class="admin-card">
                <div class="table-responsive">
                    <table class="cars-table">
                        <thead>
                            <tr>
                                <th>Tag</th>
                                <th>Totaal gebruik</th>
                                <th>Verkocht</th>
                                <th>Niet verkocht</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tags as $tag)
                                <tr>
                                    <td>
                                        <span class="tag" style="background-color: {{ $tag->color }}">
                                            {{ $tag->name }}
                                        </span>
                                    </td>
                                    <td><strong>{{ $tag->cars_count }}</strong></td>
                                    <td><span class="status sold">{{ $tag->sold_cars_count }}</span></td>
                                    <td><span class="status active">{{ $tag->unsold_cars_count }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- SECTIE 3: Aanbieders -->
        <section id="aanbieders" class="admin-section">
            <div class="section-header">
                <h1>Opvallende aanbieders</h1>
                <span class="update-badge">{{ $suspiciousUsers->count() }} aanbieders gevonden</span>
            </div>

            @if($suspiciousUsers->isEmpty())
                <div class="no-cars-message">
                    <p>Geen opvallende aanbieders gevonden.</p>
                </div>
            @else
                <div class="admin-card">
                    <div class="table-responsive">
                        <table class="cars-table">
                            <thead>
                                <tr>
                                    <th>Aanbieder</th>
                                    <th>Email</th>
                                    <th>Aantal auto's</th>
                                    <th>Meldingen</th>
                                    <th>Risico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suspiciousUsers as $suspiciousUser)
                                    <tr>
                                        <td><strong>{{ $suspiciousUser->name }}</strong></td>
                                        <td>{{ $suspiciousUser->email }}</td>
                                        <td>{{ $suspiciousUser->cars_count }}</td>
                                        <td>
                                            <div class="tags-display">
                                                @foreach($suspiciousUser->flags as $flag)
                                                    <span class="tag" style="background-color:#7F1D1D;">{{ $flag }}</span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            @php $count = count($suspiciousUser->flags); @endphp
                                            @if($count >= 3)
                                                <span class="status sold">Hoog ({{ $count }})</span>
                                            @elseif($count == 2)
                                                <span class="status" style="background:#fff3cd;color:#856404;">Gemiddeld
                                                    ({{ $count }})</span>
                                            @else
                                                <span class="status active">Laag ({{ $count }})</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </section>

    </div>


    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        let makeChartInstance, tagChartInstance, lineChartInstance, pieChartInstance;

        function renderCharts(data) {
            if (makeChartInstance) makeChartInstance.destroy();
            if (tagChartInstance) tagChartInstance.destroy();
            if (lineChartInstance) lineChartInstance.destroy();
            if (pieChartInstance) pieChartInstance.destroy();

            makeChartInstance = new CanvasJS.Chart("makeChart", {
                animationEnabled: true,
                theme: "light2",
                axisY: { gridThickness: 1 },
                data: [{ type: "bar", color: "#6f42c1", dataPoints: data.carsByMake }]
            });
            makeChartInstance.render();

            tagChartInstance = new CanvasJS.Chart("tagChart", {
                animationEnabled: true,
                theme: "light2",
                data: [{
                    type: "bar",
                    dataPoints: data.topTags.map(t => ({ label: t.label, y: t.y, color: t.color }))
                }]
            });
            tagChartInstance.render();

            lineChartInstance = new CanvasJS.Chart("lineChart", {
                animationEnabled: true,
                theme: "light2",
                axisX: { valueFormatString: "DD MMM" },
                axisY: { gridThickness: 1 },
                data: [{
                    type: "splineArea",
                    color: "#007bff",
                    fillOpacity: 0.2,
                    dataPoints: data.soldPerDay.map(d => ({ x: new Date(d.x), y: d.y }))
                }]
            });
            lineChartInstance.render();

            pieChartInstance = new CanvasJS.Chart("pieChart", {
                animationEnabled: true,
                theme: "light2",
                data: [{
                    type: "doughnut",
                    dataPoints: [
                        { label: "Verkocht", y: data.soldCars, color: "#28a745" },
                        { label: "Beschikbaar", y: data.availableCars, color: "#e6a817" },
                    ]
                }]
            });
            pieChartInstance.render();
        }

        async function fetchDashboard() {
            const res = await fetch('{{ route("admin.dashboard.data") }}');
            const data = await res.json();

            document.getElementById('totalCars').textContent = data.totalCars;
            document.getElementById('soldCars').textContent = data.soldCars;
            document.getElementById('availableCars').textContent = data.availableCars;
            document.getElementById('todayCars').textContent = data.todayCars;
            document.getElementById('totalSellers').textContent = data.totalSellers;
            document.getElementById('avgCars').textContent = data.avgCarsPerSeller;
            document.getElementById('soldPercent').textContent = data.soldPercent;
            document.getElementById('lastUpdate').textContent = data.updatedAt;
            document.getElementById('viewsToday').textContent = data.viewsToday;

            const bar = document.getElementById('progressBar');
            bar.style.width = data.soldPercent + '%';
            document.getElementById('progressLabel').textContent = data.soldPercent + '%';

            renderCharts(data);
        }

        // Scroll functie + actieve button
        function scrollToSection(id) {
            document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
            document.querySelectorAll('.admin-nav-btn').forEach(b => b.classList.remove('active'));
            document.getElementById('btn-' + id).classList.add('active');
        }

        // Actieve button updaten bij scrollen
        window.addEventListener('scroll', () => {
            ['dashboard', 'tags', 'aanbieders'].forEach(id => {
                const section = document.getElementById(id);
                const rect = section.getBoundingClientRect();
                if (rect.top <= 100 && rect.bottom >= 100) {
                    document.querySelectorAll('.admin-nav-btn').forEach(b => b.classList.remove('active'));
                    document.getElementById('btn-' + id).classList.add('active');
                }
            });
        });

        fetchDashboard();
        setInterval(fetchDashboard, 10000);
    </script>
</x-base-layout>