<div class="mt-4">
    <form action="/app/settings/change-bio" method="POST">
        @csrf

        <div class="mb-3 form-group">
                    <label for="bioInput">new bio</label>
                    <textarea type="text" class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" id="bioInput" name="bio" :value="old('bio')" required></textarea>
                    <small id="bioInput" class="form-text text-muted">Note: Your bio must be lower than 1000 characters.</small>
                    @error('bio')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

        <button class="btn btn-success" type="submit"><i class="far fa-check mr-2"></i>change bio</button>

    </form>
</div>