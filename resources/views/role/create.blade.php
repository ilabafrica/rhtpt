@extends("brio.app")

@section("content")
<div class="row">
    <div class="col-sm-12">
        <ol class="breadcrumb">
            <li><a href="{!! url('home') !!}"><i class="fa fa-home"></i> {!! trans('menu.home') !!}</a></li>
            <li class="active"><i class="fa fa-roles"></i> {!! trans('menu.access-control') !!}</li>
            <li><a href="{!! route('role.index') !!}"><i class="fa fa-cube"></i> {!! trans_choice('menu.role', 2) !!}</a></li>
            <li class="active">{!! trans('messages.new').' '.trans_choice('menu.role', 1) !!}</li>
        </ol>
    </div>
</div>
<div class="card">
	<div class="card-header">
	    <i class="fa fa-pencil"></i> {!! trans('messages.new').' '.trans_choice('menu.role', 1) !!} 
	    <span>
			<a class="btn btn-sm btn-carrot" href="#" onclick="window.history.back();return false;" alt="{!! trans('messages.back') !!}" title="{!! trans('messages.back') !!}">
				<i class="fa fa-step-backward"></i>
				{!! trans('messages.back') !!}
			</a>				
		</span>
	</div>
  	<div class="card-block">	  		  		
		<!-- if there are creation errors, they will show here -->
		@if($errors->all())
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">{!! trans('messages.close') !!}</span></button>
            {!! HTML::ul($errors->all(), array('class'=>'list-unstyled')) !!}
        </div>
        @endif
		<div class="row">
			{!! Form::open(array('route' => 'role.store', 'id' => 'form-add-role', 'class' => 'form-horizontal')) !!}
			<!-- CSRF Token -->
            <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
            <!-- ./ csrf token -->
			<div class="col-md-8"> 
				<div class="form-group row">
					{!! Form::label('name', trans_choice('messages.name',1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('name', old('name'), array('class' => 'form-control')) !!}
					</div>
				</div>
				<div class="form-group row">
					{!! Form::label('display-name', trans_choice('messages.display-name',1), array('class' => 'col-sm-4 form-control-label')) !!}
					<div class="col-sm-6">
						{!! Form::text('display_name', old('display_name'), array('class' => 'form-control')) !!}
					</div>
				</div>
                <div class="form-group row">
                    {!! Form::label('description', trans('messages.description'), array('class' => 'col-sm-4 form-control-label')) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('description', old('description'), array('class' => 'form-control', 'rows' => '3')) !!}
                    </div>
                </div>
				<div class="form-group row col-sm-offset-4 col-sm-8">
					{!! Form::button("<i class='fa fa-plus-circle'></i> ".trans('messages.save'), 
						array('class' => 'btn btn-primary btn-sm', 'onclick' => 'submit()')) !!}
					<a href="#" class="btn btn-sm btn-silver"><i class="fa fa-times-circle"></i> {!! trans('messages.cancel') !!}</a>
				</div>
			</div>
			{!! Form::close() !!}
		</div>
  	</div>
</div>
@endsection	