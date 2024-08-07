<div class="row">
    <div class="col-lg-3 mb-3">
        <label for="first_name" class="form-label">First Name <span style="color: red">*</span></label>
        <input type="text"
               class="form-control"
               id="first_name"
               name="first_name"
               required
               value="{{ isset($artistDetail) ? $artistDetail->first_name : old('first_name')}}"
               autocomplete="off"
               placeholder="First Name">
    </div>

    <div class="col-lg-3 mb-3">
        <label for="last_name" class="form-label">Last Name <span style="color: red">*</span></label>
        <input type="text"
               class="form-control"
               id="last_name"
               name="last_name"
               required
               value="{{ isset($artistDetail) ? $artistDetail->last_name : old('first_name')}}"
               autocomplete="off"
               placeholder="Last Name">
    </div>

    <div class="col-lg-3 mb-3">
        <label for="Email" class="form-label">Email <span style="color: red">*</span></label>
        <input type="text"
               class="form-control"
               id="email"
               name="email"
               required
               value="{{ isset($artistDetail) ? $artistDetail->email : old('email')}}"
               autocomplete="off"
               placeholder="Email">
    </div>

    @if(!isset($artistDetail))
        <div class="col-lg-3 mb-3">
            <label for="password" class="form-label">Password <span style="color: red">*</span></label>
            <input type="password"
                   class="form-control"
                   id="password"
                   name="password"
                   required
                   value="{{ isset($artistDetail) ? $artistDetail->password : old('password')}}"
                   autocomplete="off"
                   placeholder="password">
        </div>

        <div class="col-lg-3 mb-3">
            <label for="password confirm" class="form-label">Confirm Password <span style="color: red">*</span></label>
            <input type="password"
                   class="form-control"
                   id="password-confirm"
                   name="password_confirmation"
                   required
                   value="{{ isset($artistDetail) ? $artistDetail->password : old('password_confirmation')}}"
                   autocomplete="new-password"
                   placeholder="confirm password">
        </div>
    @endif

    <div class="col-lg-3 mb-3">
        <label for="Gender" class="form-label">Gender <span style="color: red">*</span></label>
        <select class="form-select" id="gender" name="gender" {{ isset($artistDetail) ? 'required':''}}>
            <option value="" {{ (isset($artistDetail) && $artistDetail->gender)  ? '': 'selected'}}> Select Gender </option>
            @foreach(\App\Models\User::GENDER as $key => $value)
                <option value="{{$key}}" {{ isset($artistDetail) && ($artistDetail->gender ) == $key || !is_null(old('gender')) && old('gender') == $key ?'selected': '' }}>
                    {{ucfirst($value)}}
                </option>
            @endforeach
        </select>
    </div>


    <div class="col-lg-3 mb-3">
        <label for="dob" class="form-label">DOB<span style="color: red">*</span></label>
        <input type="date"
               class="form-control"
               id="dob"
               name="dob"
               required
               value="{{ isset($artistDetail) ? $artistDetail?->dob : old('dob')}}"
               autocomplete="off" >
    </div>

    <div class="col-lg-3 mb-3">
        <label for="first_release_year" class="form-label">Firt Release Year<span style="color: red">*</span></label>
        <input type="date"
               class="form-control"
               id="first_release_year"
               name="first_release_year"
               value="{{ isset($artistDetail) ? $artistDetail?->first_release_year : old('dob')}}"
               autocomplete="off" >
    </div>

    <div class="col-lg-3 mb-3">
        <label for="address" class="form-label">Address<span style="color: red">*</span></label>
        <input type="text"
               class="form-control"
               id="address"
               name="address"
               required
               value="{{ isset($artistDetail) ? $artistDetail?->address : old('address')}}"
               autocomplete="off" >
    </div>



    <div class="col-lg-12">
        <button type="submit" class="btn btn-success btn-md">
            <i class="link-icon" data-feather="{{isset($artistDetail)? 'edit-2':'plus'}}"></i>
            {{ $buttonName }}
        </button>
    </div>

</div>

<script>
    $(document).ready(function() {

    });
</script>







