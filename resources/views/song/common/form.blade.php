<div class="row">
    <div class="col-lg-3 mb-3">
        <input type="hidden" name="artist_id" readonly value="{{$artistId}}"/>
        <label for="title" class="form-label">Title<span style="color: red">*</span></label>
        <input type="text"
               class="form-control"
               id="title"
               name="title"
               required
               value="{{ isset($songDetail) ? $songDetail->title : old('title')}}"
               autocomplete="off"
               placeholder="Song Title">
    </div>

    <div class="col-lg-3 mb-3">
        <label for="Album Name" class="form-label">Album Name<span style="color: red">*</span></label>
        <input type="text"
               class="form-control"
               id="album_name"
               name="album_name"
               required
               value="{{ isset($songDetail) ? $songDetail->album_name : old('album_name')}}"
               autocomplete="off"
               placeholder="Album Name">
    </div>


    <div class="col-lg-3 mb-3">
        <label for="Genre" class="form-label">Genre <span style="color: red">*</span></label>
        <select class="form-select" id="genre" name="genre" {{ isset($songDetail) ? 'required':''}}>
            <option value="" {{ (isset($songDetail) && $songDetail->genre)  ? '': 'selected'}}> Select Genre </option>
            @foreach(\App\Models\Song::GENRE as  $value)
                <option value="{{$value}}" {{ isset($songDetail) && ($songDetail->genre ) == $value || !is_null(old('genre')) && old('genre') == $value ?'selected': '' }}>
                    {{ucfirst($value)}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-12">
        <button type="submit" class="btn btn-success btn-md">
            <i class="link-icon" data-feather="{{isset($songDetail)? 'edit-2':'plus'}}"></i>
            {{ $buttonName }}
        </button>
    </div>

</div>

<script>
    $(document).ready(function() {

    });
</script>







