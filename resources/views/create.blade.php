@section('title', 'Create')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'create new asset'">
            <p class="text-muted fw-regular">Please select what asset type you want to create.</p>

            <button class="btn btn-success" onclick="document.location = '/app/create/place'"><i class='far fa-trophy-alt mr-3'></i>place</button>
            <button class="btn btn-success" onclick="document.location = '/app/create/asset'"><i class='far fa-child mr-3'></i>asset</button>
        </x-card>
    </div>
</x-app-layout>