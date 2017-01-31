@extends("app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
        </ol>
    </div>
</div>
<div class="card">
  <img src="{{ (count(Request::segments())>1)?'../../'.Config::get('cms.dashboard'):Config::get('cms.dashboard') }}" class= "img-responsive" width= "80%" alt="Card image">
</div>
{!! session(['SOURCE_URL' => URL::full()]) !!}
@endsection
