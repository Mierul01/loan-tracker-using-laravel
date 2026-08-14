@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-error">{{ $errors->first() }}</div>
@endif
