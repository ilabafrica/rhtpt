@extends("app")

@section("content")
<div class="row">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-dashboard"></i> {!! trans_choice('messages.home', 1) !!}</a></li>
            <li class="active"><i class="fa fa-group"></i> {!! trans('messages.user-management') !!}</li>
            <li class="active"><i class="fa fa-cube"></i> {!! trans_choice('messages.permission', 1) !!}</li>
        </ol>
    </div>
</div>
<div class="card">
    <div class="card-header">
        <i class="fa fa-book"></i> {!! trans_choice('messages.permission', 2) !!}
        <span>
            <a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
                <i class="fa fa-step-backward"></i> {!! trans('messages.back') !!}
            </a>
        </span>
    </div>
    <div class="card-block">
        @if (Session::has('message'))
            <div class="alert alert-info">{!! Session::get('message') !!}</div>
        @endif
        @if($errors->all())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('messages.close') !!}</span></button>
                {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
            </div>
        @endif
        <table class="table table-bordered table-sm search-table" id="example">
            <thead>
                <tr>
                    <th>{!! trans_choice('messages.name', '1') !!}</th>
                    <th>{!! trans_choice('messages.description', '1') !!}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $permission)
                <tr
                    @if(session()->has('active_permission'))
                    {!! (session('active_permission') == $permission->id)?"class='warning'":"" !!}
                    @endif
                    >
                    <td>{!! $permission->name !!}</td>
                    <td>{!! $permission->display_name !!}</td>
                    <td>
                        <a href="{!! URL::to("permission/" . $permission->id) !!}" class="btn btn-success btn-sm"><i class="fa fa-eye"></i><span> View</span></a>
                        @if(Auth::user()->can('manage-permission'))
                        <a href="{!! URL::to("permission/" . $permission->id . "/edit") !!}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i><span> Edit</span></a>
                        <!-- <a href="{!!URL::to("permission/" . $permission->id . "/delete") !!}" class="btn btn-warning btn-sm"><i class="fa fa-trash-o"></i><span> Delete</span></a>-->
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3">{!! trans('messages.no-records-found') !!}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
