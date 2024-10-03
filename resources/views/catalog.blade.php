@section('title', 'Catalog')
<x-app-layout>
    <div class="container mt-5">

        <h3 class="fw-bold">Catalog</h3>
        <h6 class="fw-regular text-muted mb-4">Buy cool items to make your avatar better.</h6>

        <div class="row">
            <div class="col-md-3">
                <form action="/app/catalog/search" method="GET" class="mb-3">
                    <input type="text" class="form-control w-100 mb-2" id="searchInput" name="search" value="{{ $search ?? '' }}" placeholder="Search">
                    <button class="btn btn-sm btn-success w-100"><i class="far fa-search mr-2"></i> search</button>
                </form>

                <h6 class="text-muted"><span class="mr-2">⎯</span> <i>sort by...</i></h6>

                <h5 class="mb-2">
                    <a href="/app/catalog/hats" class="text-success">⯈ hats</a><br>
                    <a href="/app/catalog/heads" class="text-success">⯈ heads</a><br>
                    <a href="/app/catalog/shirts" class="text-success">⯈ shirts</a><br>
                    <a href="/app/catalog/pants" class="text-success">⯈ pants</a><br>
                    <a href="/app/catalog/tshirts" class="text-success">⯈ t-shirts</a><br>
                    <a href="/app/catalog/faces" class="text-success">⯈ faces</a><br>
                    <a href="/app/catalog/gears" class="text-success">⯈ gears</a><br>
                </h5>

                
                <h6 class="text-muted"><span class="mr-2">⎯</span> <i>creator marketplace</i></h6>

                <h5>
                    <a href="/app/catalog/audios" class="text-success">⯈ audios</a><br>
                    <a href="/app/catalog/models" class="text-success">⯈ models</a><br>
                    <a href="/app/catalog/decals" class="text-success">⯈ decals</a><br>
                </h5>
            </div>
            <div class="col">
            @if ($items->isEmpty())

            <div class="text-center">
            <img src="/images/neutral.png" alt="Neutral face" width="46" class="mb-2">
            <p class="text-center text-muted">There aren't any items here.</p>
            </div>

            @else 
            <div class="row">
            @foreach ($items as $item)
            <div class="col-2 mb-2 px-2">
                <x-catalog-card :title="$item->name" :creatorName="$item->user->name" :creatorId="$item->user->id" :special="$item->special" :status="$item->peeps . ' Peeps'" :id="$item->id" :price="$item->peeps" :offsale="$item->off_sale" :under_review="$item->under_review" :type="$item->type" />
            </div>
            @endforeach
            </div>
            <div class="mt-3 d-flex justify-content-center w-100">
                {{ $items->links() }}
            </div>
            @endif
            </div>
        </div>

    </div>


    <script src="/functions.js"></script>
</x-app-layout>