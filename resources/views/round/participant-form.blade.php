@extends('app')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('messages.home') !!}</a></li>
            <li class="active"><i class="fa fa-cubes"></i> {!! trans('messages.pt') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.participant', 2) !!}</li>
        </ol>
    </div>
</div>
<?php
    $fileName = "img/Participant-Form.pdf";
    $formFile = Zend_Pdf::load($fileName);
?>
<div id="participant-form">
    <!-- Round Listing -->
    <div class="row">
    @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif
    </div>

    <div class="row">
        <div class="col-md-12">
        </div>
    </div>
</div>
@endsection

