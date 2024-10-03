@section('title', 'Avatar Editor')
<x-app-layout>
    <link href="/BodyColors.css" rel="stylesheet">
    @include('avatar.bodycolors_modal')
   
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <x-card :title="'Character Preview'">
                    <img src="/cdn/users/{{ Auth::id() }}?t={{ time() }}" alt="Your Character" id="avatar-preview" width="100%" class="mb-3">
                    <button class="btn btn-success w-100" id="regen-button" onclick="Regenerate()">
                        <i class="far fa-sync mr-2" id="spinnyfuny"></i>
                        <span id="regen-text">Regenerate</span>
                    </button>
                </x-card>
            </div>
            <div class="col">
                <x-card :title="'Editor'">
                    <ul class="nav nav-tabs" id="avatarEditorTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="items-tab" data-toggle="tab" data-target="#items" type="button" role="tab" aria-controls="items" aria-selected="true">Items</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="wearing-tab" data-toggle="tab" data-target="#wearing" type="button" role="tab" aria-controls="wearing" aria-selected="false">Wearing</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="bodycolors-tab" data-toggle="tab" data-target="#bodycolors" type="button" role="tab" aria-controls="bodycolors" aria-selected="false">Bodycolors</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="avatarEditorTabContent">
                        <div class="tab-pane fade show active" id="items" role="tabpanel" aria-labelledby="items-tab">
                            @if ($items->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You have no items in your inventory.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($items as $item)
                                        <div class="col-md-2 mb-4">
                                                @if ($item->asset->type == "tshirt" || $item->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $item->asset->id - 1 }}&t={{ time() }}" alt="{{ $item->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $item->asset->id }}?t={{ time() }}" alt="{{ $item->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $item->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $item->asset->id }})" id="wear-btn-{{ $item->asset->id }}" data-wearing-item="false" data-wearing="{{ $item->wearing }}" >{{ $item->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $items->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="wearing" role="tabpanel" aria-labelledby="wearing-tab">
                            @if ($wearing_items->isEmpty()) 
                                <p class="mb-0 text-center text-muted mt-3">You aren't wearing anything.</p>
                            @else
                                <div class="row mt-3">
                                    @foreach ($wearing_items as $wear)
                                        <div class="col-md-2 mb-4" id="item-{{ $wear->asset->id }}">
                                                @if ($wear->asset->type == "tshirt" || $wear->asset->type == "face")
                                                <img src="/images/loading.png" data-src="/asset/?id={{ $wear->asset->id - 1 }}&t={{ time() }}" alt="{{ $wear->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @else
                                                <img src="/images/loading.png" data-src="/cdn/{{ $wear->asset->id }}?t={{ time() }}" alt="{{ $wear->asset->name }}" class="mb-1 rounded-sm border mb-2 lazy-load" width="100" height="100">
                                                @endif
                                                
                                                <p class="mb-2 text-truncate text-center">{{ $wear->asset->name }}</p>
                                                <button class="btn btn-sm btn-success w-100" onclick="wear({{ $wear->asset->id }})" id="wear-btn-{{ $wear->asset->id }}" data-wearing-item="true" data-wearing="{{ $wear->wearing }}" >{{ $wear->wearing == 1 ? 'Unwear' : 'Wear' }}</button>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 mb-0 d-flex justify-content-center w-100">
                                    {{ $wearing_items->links() }}
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="bodycolors" role="tabpanel" aria-labelledby="bodycolors-tab">
                            <div class="my-4 d-flex justify-content-center">
                                <div style="height: 240px; width: 194px; text-align: center;">
                                    <div style="position: relative; margin: 11px 4px; height: 100%;">
                                        <div style="position: absolute; left: 72px; top: 0px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->head }}" id="head" onclick="SetBodyPart('head')" data-toggle="modal" data-target="#colorPickerModal" style="height: 44px; width: 44px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 0px; top: 52px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->larm }}" id="larm" onclick="SetBodyPart('larm')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 48px; top: 52px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->torso }}" id="torso" onclick="SetBodyPart('torso')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 88px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 144px; top: 52px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->rarm }}" id="rarm" onclick="SetBodyPart('rarm')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 48px; top: 146px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->lleg }}" id="lleg" onclick="SetBodyPart('lleg')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                        <div style="position: absolute; left: 96px; top: 146px; cursor: pointer;">
                                            <div class="border border-secondary brick-color-{{ $bodyColors->rleg }}" id="rleg" onclick="SetBodyPart('rleg')" data-toggle="modal" data-target="#colorPickerModal" style="height: 88px; width: 40px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <script src="/functions.js"></script>
    <script>
        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        window.regenerating = false;

        async function Regenerate() {
            let spinnyfuny = document.getElementById('spinnyfuny');
            let regenbutton = document.getElementById('regen-button');
            let regentext = document.getElementById('regen-text');
            let avatar = document.getElementById('avatar-preview');

            if (window.regenerating == true) {
                return;
            }

            window.regenerating = true;

            regenbutton.disabled = true;
            regentext.innerHTML = "Regenerating";
            spinnyfuny.classList.add('fa-spin');

            avatar.src = "/images/loader2.gif";

            await fetch('/test/{{ Auth::id() }}');

            regenbutton.disabled = false;
            regentext.innerHTML = "Regenerate";
            spinnyfuny.classList.remove('fa-spin');

            avatar.src = "/cdn/users/{{ Auth::id() }}?t=" + Date.now();

            console.log("Regeneration complete");

            window.regenerating = false;
        }

        async function wear(id) {
    try {

        const allButtons = document.querySelectorAll('[id^="wear-btn-"]');
        allButtons.forEach(button => button.disabled = true);

        const response = await fetch(`/app/wear-item/${id}`);
        if (!response.ok) {
            throw new Error(`Response status: ${response.status}`);
        }

        const button = document.getElementById(`wear-btn-${id}`);
        if (button) {
            const wearing = button.getAttribute('data-wearing') === '1';

            button.textContent = wearing ? 'Wear' : 'Unwear';
            button.setAttribute('data-wearing', wearing ? '0' : '1');

            await Regenerate();

            location.reload();
        }
    } catch (error) {
        console.error('Error:', error.message);
    }
}


        function SetBodyPart(part) {
            window.bPart = part; 
        }

        async function ChangeBodyColor(part, code) {
            console.log('[BodyColors] - Part : ' + part + ' Color : ' + code);
    
            let element = document.getElementById(part);
    
            element.classList.forEach(className => {
                if (className.startsWith('brick-color-')) {
                    element.classList.remove(className);
                }
            });

            await fetch('/app/change-body-color/' + code + '/' + part);

            element.classList.add('brick-color-' + code);
        }
    </script>
</x-app-layout>
