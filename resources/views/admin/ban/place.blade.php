@section('title', 'Moderation')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'ban place'">
            <p class="fw-regular text-muted">Ban a place. This action is <i class="text-danger">destructive</i> and can get you demoted.</p>

            <form action="/app/admin/api/ban-user" method="POST">
                <div class="form-group mb-2">
                        <input type="text" name="placeId" class="form-control" id="placeidtoban" placeholder="Place id to ban">
                </div>

                <button class="btn btn-danger">ban</button>

            </form>
        </x-card>
    </div>
</x-app-layout>