{{ csrf_field() }}

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label" for="name">Nombre: </label>
    <input class="col-sm-10" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
</div>

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label" for="email">Email: </label>
    <input class="col-sm-10" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
</div>

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label" for="profession_id">Profesión: </label>
    <select class="col-sm-10" name="profession_id" id="profession_id">

        <option value="">Selecciona una profesión</option>

        @foreach ($professions as $profession)
            <option value="{{ $profession->id }}"
                {{ $profession->id == old('profession_id', $user->profile->profession_id) ? ' selected' : '' }}>
                {{ $profession->title }}
            </option>
        @endforeach

    </select>
</div>

<div class="form-group row mb-3">

    <div class="col-sm-2">Habilidades:</div>
    <div class="col-sm-10">

        @foreach ($skills as $skill)
            <div class="form-check-inline">
                <input name="skills[{{ $skill->id }}]" class="form-check-input" type="checkbox"
                    id="skill_{{ $skill->id }}" value="{{ $skill->id }}"
                    {{ ($errors->any() ? old("skills.{$skill->id}") : $user->skills->contains($skill)) ? ' checked' : '' }}>
                <label class="form-check-label" for="skill_{{ $skill->id }}">
                    {{ $skill->name }}
                </label>
            </div>
        @endforeach

    </div>
</div>

<div class="form-group row mb-3">

    <div class="col-sm-2">Rol:</div>
    <div class="col-sm-10">

        @foreach ($roles as $role => $name)
            <div class="form-check-inline">
                <input class="form-check-input" type="radio" id="role_{{ $role }}" name="role"
                    value="{{ $role }}" {{ old('role', $user->role) == $role ? ' checked' : '' }}>
                <label class="form-check-label" for="role_{{ $role }}">
                    {{ $name }}
                </label>
            </div>
        @endforeach

    </div>
</div>

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label" for="twitter">Twitter: </label>
    <input class="col-sm-10" type="url" name="twitter" id="twitter"
        value="{{ old('twitter', $user->profile->twitter) }}">
</div>

<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label" for="bio">Bio: </label>
    <textarea class="col-sm-10" name="bio" id="bio" cols="30"
        rows="10">{{ old('bio', $user->profile->bio) }}</textarea>
</div>



<div class="form-group row mb-3">
    <label class="col-sm-2 col-form-label" for="password">Contraseña: </label>
    <input class="col-sm-10" type="password" name="password" id="password">
</div>
