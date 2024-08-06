@if (isset($errors) && count($errors) > 0)
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif


@if (Session::has('success'))
    <div style="max-width: 100%" id="message"   class="alert alert-success {{Session::has('success_important') ? 'alert-important': ''}} ">
        @if(Session::has('success_important'))
        @endif
        {{session('success')}}
    </div>
@endif

@if (Session::has('danger'))
    <div style="max-width: 100%" id="message" class="alert alert-danger {{Session::has('danger_important') ? 'alert-important': ''}}">
        @if(Session::has('danger_important'))
        @endif
        {{session('danger')}}
    </div>
@endif


