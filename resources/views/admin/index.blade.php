<x-base-layout>
<div class="owncars-container">
    <div class="owncars-header">
        <h1>Tag overzicht</h1>
    </div>

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
                    <td>
                        <span class="status sold">{{ $tag->sold_cars_count }}</span>
                    </td>
                    <td>
                        <span class="status active">{{ $tag->unsold_cars_count }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-base-layout>