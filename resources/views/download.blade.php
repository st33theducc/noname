@section('title', 'Download')
<x-app-layout>
    <div class="container mt-5">
        <x-card :title="'download'">
            <p class="fw-regular text-muted mb-2">You can download the global bootstrapper. Run it and it will install {{ config('app.name', 'Laravel') }} to your computer. <strong>Note:</strong> Keep the bootstrapper in the same location. Otherwise, the client will not start.</p>
            
            <button class="btn btn-success w-100" onclick="document.location = '/NONAMEBootstrapper.exe'">Get the client</button>
            <!--<x-loader />-->
        </x-card>
    </div>
</x-app-layout>