@extends("error")

@section("content")
    <h2 class="headline text-info"> 403</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Oops! Unauthorized Action.</h3>
        <p>
            Access Denied. Meanwhile, you may <a href="{{ url('welcome') }}">return to dashboard</a>.
        </p>
    </div>
@endsection
