

<!-- Validation errors -->
@if (isset($errors) && $errors->count())
    <div class="alert alert-danger alert-dismissible mt-30 mt-15-xs
 text-left" role="alert">
        <h3 class="text-danger">Merci de modifier vos informations</h3>
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Success messages -->
@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible mt-30 mt-15-xs
 text-left" role="alert">
        {!!  session('success')  !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<!-- Validation Status -->
@if (Session::has('status'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ Session::get('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
