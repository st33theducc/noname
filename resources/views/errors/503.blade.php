@section('title', 'Error')
<x-guest-layout>
    <style>
        #footer-container-funny {
            display: none !important;
        }
    </style>
    <div class="container mt-5">

    <div class="p-2 font-weight-bolder mb-3 bg-danger text-white"><marquee>BREAKING NEWS: UNBANNING EVERYONE</marquee></div>

        <x-card :title="'maintenance'">
            <div class="d-flex justify-content-center align-items-center my-5 mx-5">
                <div class="text-center">

                    <img src="/images/spinningpeep.gif" alt="Spinning peep" width="150" class="mb-3">

                    <h3 class="fw-medium">we're updating</h3>
                    <h6 class="fw-light text-muted"><marquee>new features coming soon :3</marquee></h6>
                </div>
            </div>
        </x-card>
    </div>
</x-guest-layout>
