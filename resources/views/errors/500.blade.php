@extends("error")

@section("content")
    <h2 class="headline text-info"> 500</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> Oops! Something went wrong.</h3>
        <p>
            <a href="{{ url('welcome') }}">Return to dashboard</a>.
        </p>
    </div>
@endsection
