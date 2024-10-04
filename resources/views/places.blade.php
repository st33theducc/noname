@section('title', 'Places')
<x-app-layout>
    <div class="container">
        <h3 class="fw-bolder mt-5">Places</h3>
        <h6 class="fw-regular text-muted mb-4">See all places made by NONAME users just like you</h6>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a id="places-tab" class="nav-link active" data-toggle="tab" href="#places" role="tab" aria-controls="places" aria-selected="true">Places</a>
    </li>
    <li class="nav-item">
        <a id="featured-tab" class="nav-link" data-toggle="tab" href="#featured" role="tab" aria-controls="featured" aria-selected="false">Featured</a>
    </li>
</ul>

<div id="loading-container" class=" text-center mt-4">
        
    </div>

<div class="tab-content" id="myTabContent">
    <div id="places" class="tab-pane fade show active mt-4" role="tabpanel" aria-labelledby="places-tab">
        
    </div>
    
    
    <div id="featured" class="tab-pane fade mt-4" role="tabpanel" aria-labelledby="featured-tab">
        
    </div>
</div>

<div id="places-pagination" class="d-flex justify-content-center mt-4"></div>

<script>
    let currentPage = 1;

async function loadPlaces(page = 1) {
    showLoading("places-tab"); 

    const response = await fetch(`/app/get-places?page=${page}`);
    const placesData = await response.json();
    const placesContainer = document.getElementById('places');
    placesContainer.innerHTML = ''; 

    placesData.data.forEach(place => {
        const card = generatePlaceCard(place);
        placesContainer.appendChild(card);
    });

    renderPagination(placesData.pagination, loadPlaces);
    hideLoading("places-tab"); 
}

async function loadFeatured(page = 1) {
    showLoading("featured-tab"); 

    const response = await fetch(`/app/get-featured-places?page=${page}`);
    const featuredData = await response.json();
    const featuredContainer = document.getElementById('featured');
    featuredContainer.innerHTML = ''; 

    featuredData.data.forEach(place => {
        const card = generatePlaceCard(place);
        featuredContainer.appendChild(card);
    });

    renderPagination(featuredData.pagination, loadFeatured);
    hideLoading("featured-tab"); 
}

function renderPagination(pagination, loadFunction) {
    const paginationContainer = document.getElementById('places-pagination');
    paginationContainer.innerHTML = ''; 

    const { current_page, last_page } = pagination;

    const prevButton = document.createElement('button');
    prevButton.classList.add('btn', 'btn-secondary', 'mx-1');
    prevButton.innerText = 'Previous';
    prevButton.disabled = current_page === 1;
    prevButton.onclick = () => loadFunction(current_page - 1);
    paginationContainer.appendChild(prevButton);

    for (let i = 1; i <= last_page; i++) {
        const pageButton = document.createElement('button');
        pageButton.classList.add('btn', 'mx-1', i === current_page ? 'btn-primary' : 'btn-secondary');
        pageButton.innerText = i;
        pageButton.onclick = () => loadFunction(i);
        paginationContainer.appendChild(pageButton);
    }

    const nextButton = document.createElement('button');
    nextButton.classList.add('btn', 'btn-secondary', 'mx-1');
    nextButton.innerText = 'Next';
    nextButton.disabled = current_page === last_page;
    nextButton.onclick = () => loadFunction(current_page + 1);
    paginationContainer.appendChild(nextButton);
}

function generatePlaceCard(place) {
    const card = document.createElement('div');
    card.className = 'card place-card clickable';
    card.style.cursor = 'pointer';
    card.onclick = () => window.location.href = `/app/place/${place.id}`;

    card.innerHTML = `
        <div class="position-relative">
            <div class="more-info position-absolute pl-1" style="z-index: 3;">
                <span class="badge badge-secondary">by ${place.creator}</span> <br> 
                <span class="badge badge-success">${place.visits} visits</span> <br> 
                <span class="badge badge-danger">${place.year}</span>
            </div>
            <div class="position-absolute top-0 start-0 w-100 h-100"></div>
            ${place.under_review == 1 
                ? `<img src="/images/place_pending.png" alt="Game Thumbnail" class="card-img place-thumbnail border-bottom lazy-load" height="150">`
                : `<img src="/images/placeload.png" data-src="/cdn/${place.id}?t=${Date.now()}" alt="Game Thumbnail" class="card-img place-thumbnail border-bottom lazy-load" height="150">`}
        </div>

        <div class="card-body p-3">
            <h5 class="fw-bold mb-1 text-truncate">${place.title}</h5>
            <h6 class="fw-regular text-muted mb-1">by ${place.creator}</h6>
            <h6 class="fw-regular text-muted mb-0">${place.status} playing</h6>
        </div>
    `;
    return card;
}

document.addEventListener('DOMContentLoaded', function () {
    loadPlaces(currentPage);  

    $('#places-tab').on('click', function () {
        $('#featured').empty(); 
        loadPlaces(currentPage);
    });

    $('#featured-tab').on('click', function () {
        $('#places').empty(); 
        loadFeatured(currentPage);
    });
});

function showLoading(classthing) {
    const loadingSpinner = '<img src="/images/loader.png" alt="Loading blob" width="50" class="loader">';
    let loadingContainer = document.getElementById('loading-container');

    loadingContainer.style.display = "block";
    loadingContainer.innerHTML = loadingSpinner;
}

function hideLoading() {
    let loadingContainer = document.getElementById('loading-container');

    loadingContainer.style.display = "none";
    loadingContainer.innerHTML = '';
}
</script>
    <script src="/functions.js"></script>
</x-app-layout>
