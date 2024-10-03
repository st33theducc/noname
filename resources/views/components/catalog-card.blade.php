@props(['title', 'under_review', 'status', 'creator', 'visits', 'year', 'id', 'thumbnail', 'price', 'offsale', 'type', 'imgsize', 'fromadmin', 'special', 'creatorId', 'creatorName'])

@php
$fromadmin = $fromadmin ?? "pp"; 
$types = ['model', 'audio', 'decal'];
$creatorId = $creatorId ?? -69;
$creatorName = $creatorName ?? "Notdefinedontshow";
@endphp

@if ($fromadmin == "true")
<div class="card place-card clickable" onclick="document.location = '/app/admin/view/{{ $id }}'">
@else
<div class="card place-card clickable" onclick="document.location = '/app/{{ in_array($type, $types) ? 'model' : 'item' }}/{{ $id }}'">
@endif
    <div class="position-relative">
    @if ($price > 499)
    <img src="/images/limited.png" alt="Limited" class="position-absolute bottom-1 fixed-bottom" width="50">
    @endif
    @if ($special == 1)
    <img src="/images/special.png" alt="Limited" class="position-absolute bottom-1 fixed-bottom" width="50">
    @endif
    @if ($type == "audio")
    <img src="/images/audio.png" alt="Item Thumbnail" class="card-img place-thumbnail {{ $fromadmin == 'false' ? 'ugc' : '' }} border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($type == "decal" || $type == "tshirt" || $type == "face")
    <img class="lazy-load" src="/images/load.gif" data-src="/asset/?id={{ $id - 1 }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @elseif ($fromadmin == "true" && $type == "shirt" && $type == "pants")
    <img class="lazy-load" src="/images/load.gif" data-src="/asset/?id={{ $id - 1 }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @else
    <img class="lazy-load" src="/images/load.gif" data-src="/cdn/{{ $id }}?t={{ time() }}" alt="Item Thumbnail" class="card-img place-thumbnail border-bottom" height="{{ $imgsize ?? 120 }}" width="100%">
    @endif
</div>
    <div class="p-3">
        <h6 class="fw-bold {{ in_array($type, $types) ? 'mb-0' : 'mb-1' }} text-truncate" title="{{ $title }}">{{ $title }}</h6>
        @if (!in_array($type, $types)) 
        <h6 class="fw-regular text-muted mb-0">
            @if ($price == 0)
                Free
            @elseif ($offsale == 1)
                Off-Sale
            @else
                {{ $status }}
            @endif
        @endif
        </h6>
        @if ($creatorId !== -69 && $creatorName !== "Notdefinedontshow")
        <h6 class="fw-regular text-muted mb-0 text-truncate" title="by {{ $creatorName }}">by <a href="/app/user/{{ $creatorId }}">{{ $creatorName }}</a></h6>
        @endif
    </div>
</div>
