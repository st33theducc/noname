@props(['title', 'under_review', 'status', 'creator', 'visits', 'year', 'id', 'thumbnail'])

<div class="card place-card clickable" onclick="document.location = '/app/place/{{ $id }}'">
    <div class="position-relative">
        <div class="more-info position-absolute pl-1" style="z-index: 3;">
            <span class="badge badge-secondary">by {{ $creator }}</span> <br> <span class="badge badge-success">{{ $visits }} visits</span> <br> <span class="badge badge-danger">{{ $year }}</span>
        </div>
        <div class="position-absolute top-0 start-0 w-100 h-100"></div>
        @if ($under_review == 1)
        <img src="/images/place_pending.png" alt="Game Thumbnail" class="card-img place-thumbnail border-bottom lazy-load" height="150">
        @else
        <img src="/images/placeload.png" data-src="/cdn/{{ $id }}?t={{ time() }}" alt="Game Thumbnail" class="card-img place-thumbnail border-bottom lazy-load" height="150">
        @endif
    </div>

    <div class="card-body p-3">
        <h5 class="fw-bold mb-1 text-truncate">{{ $title }}</h5>
        <h6 class="fw-regular text-muted mb-1">by {{ $creator }}</h6>
        <h6 class="fw-regular text-muted mb-0">{{ $status }} playing</h6>
    </div>
</div>
