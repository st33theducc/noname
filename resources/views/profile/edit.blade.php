@section('title', 'Settings')

<div id="modal-container">
<div class="modal fade" id="buyItemModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <p class="text-muted fw-regular" id="modal-message">
            Do you want to buy <strong>"1 place slot"</strong> for 250 <x-peep-icon :spacing="true" :size="16" />?
        </p>

        <img src="/images/twisted.png" alt="Item Thumbnail" width="200" height="200" class="place-thumbnail border rounded-sm my-3">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" onclick="refresh()">Close</button>
        <button type="button" class="btn btn-success" id="modal-buy-button" onclick="buySlot()">Buy slot</button>
      </div>
    </div>
  </div>
</div>
</div>

<x-app-layout>
   <div class="container mt-5">
      <x-card :title="'Settings'">
         <div class="row">
            <div class="col-md-3">
            <div class="nav flex-column nav-pills text-left" id="v-pills-tab" role="tablist" id="myTab" aria-orientation="vertical">
                <button class="nav-link active text-left" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true"><i class="far fa-user mr-2"></i>Profile</button>
                <button class="nav-link text-left" id="theme-tab" data-toggle="tab" data-target="#theme" type="button" role="tab" aria-controls="theme" aria-selected="false"><i class="far fa-pencil-paintbrush mr-2"></i> Theming</button>
                <button class="nav-link text-left" id="settings-tab" data-toggle="tab" data-target="#settings" type="button" role="tab" aria-controls="settings" aria-selected="false"><i class="far fa-cog mr-2"></i> Settings</button>
</div>
            </div>
            <div class="col">
               <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                     @include('profile.partials.update-password-form')
                     @include('profile.partials.update-bio')
                  </div>
                  <div class="tab-pane fade" id="theme" role="tabpanel" aria-labelledby="theme-tab">

                     <div class="mb-0">
                        <h4 class="font-weight-light">change theme</h4>
                        <h6 class="font-weight-regular text-muted mb-3">Give the site a new look.</h6>
                        <form action="/app/theming/change" method="GET">
                           <div class="mb-3 form-group">
                              <label for="theme">theme</label>
                              <select class="form-control" name="type" id="theme">
                              <option value="0" {{ Auth::user()->theme == 0 ? 'selected' : '' }}>None</option>
                                <option value="3" {{ Auth::user()->theme == 3 ? 'selected' : '' }}>ectoBiologist FAIL</option>
                                <option value="1" {{ Auth::user()->theme == 1 ? 'selected' : '' }}>Crack!!!</option>
                                <option value="2" {{ Auth::user()->theme == 2 ? 'selected' : '' }}>Dark mode</option>

                              </select>
                           </div>
                           <button class="btn btn-success">Change theme</button>
                        </form>
                     </div>
                     
                  </div>

                  <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                     <div class="mb-0">
                        <h4 class="font-weight-light">get more place slots</h4>
                        <h6 class="font-weight-regular text-muted"> You have {{ Auth::user()->place_slots_left }} place slots left. </h6>
                        <h6 class="font-weight-regular text-muted mb-3">1 place slot costs <x-peep-icon :size="'16'" :spacing="true" /> 250 </h6>

                        <button class="btn btn-success" data-toggle="modal" data-target="#buyItemModal">buy 1 place slot</button>
                     </div>
                  </div>

               </div>
            </div>
         </div>
      </x-card>
   </div>

   <script src="/functions.js"></script>
</x-app-layout>
