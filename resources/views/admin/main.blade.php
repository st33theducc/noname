@section('title', 'Admin Panel')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'Admin Panel'">
            <p class="fw-regular text-muted">Welcome to the Admin Panel. Server time: {{ date('Y-m-d H:i:s') }}</p>

            <div class="row">

                <div class="col-md-2 mb-4 text-info clickable">
                    <div class="card card-body border-info text-center" onclick="document.location = '/app/admin/render'">
                        <h1 class="mb-2"><i class="far fa-draw-circle"></i></h1>

                        <p class="mb-0 fw-regular">render asset</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-danger clickable">
                    <div class="card card-body border-danger text-center">
                        <h1 class="mb-2"><i class="far fa-user-slash"></i></h1>

                        <p class="mb-0 fw-regular">ban user</p>
                    </div>
                </div>
                
                <div class="col-md-2 mb-0 text-danger clickable">
                    <div class="card card-body border-danger text-center">
                        <h1 class="mb-2"><i class="far fa-gavel"></i></h1>

                        <p class="mb-0 fw-regular">ban place</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-danger clickable">
                    <div class="card card-body border-danger text-center">
                        <h1 class="mb-2"><i class="far fa-user-shield"></i></h1>

                        <p class="mb-0 fw-regular">make admin</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-success clickable" onclick="document.location = '/app/admin/moderation'">
                    <div class="card card-body border-success text-center">
                        <h1 class="mb-2"><i class="far fa-child"></i></h1>

                        <p class="mb-0 fw-regular">pending assets</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-success clickable">
                    <div class="card card-body border-success text-center">
                        <h1 class="mb-2"><i class="far fa-trophy"></i></h1>

                        <p class="mb-0 fw-regular">pending places</p>
                    </div>
                </div>
                
                <div class="col-md-2 mb-4 text-info clickable">
                    <div class="card card-body border-info text-center">
                        <h1 class="mb-2"><i class="far fa-server"></i></h1>

                        <p class="mb-0 fw-regular">manage jobs</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-info clickable" onclick="document.location = '/app/admin/create/hat'">
                    <div class="card card-body border-info text-center">
                        <h1 class="mb-2"><i class="far fa-plus"></i></h1>

                        <p class="mb-0 fw-regular">create hat</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-info clickable" onclick="document.location = '/app/admin/create/face'">
                    <div class="card card-body border-info text-center">
                        <h1 class="mb-2"><i class="far fa-plus"></i></h1>

                        <p class="mb-0 fw-regular">create face</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-info clickable" onclick="document.location = '/app/admin/create/gear'">
                    <div class="card card-body border-info text-center">
                        <h1 class="mb-2"><i class="far fa-plus"></i></h1>

                        <p class="mb-0 fw-regular">create gear</p>
                    </div>
                </div>

                <div class="col-md-2 mb-4 text-info clickable" onclick="document.location = '/app/admin/create/head'">
                    <div class="card card-body border-info text-center">
                        <h1 class="mb-2"><i class="far fa-plus"></i></h1>

                        <p class="mb-0 fw-regular">create head</p>
                    </div>
                </div>

            </div>
        </x-card>
    </div>

    <script src="/functions.js"></script>
</x-app-layout>