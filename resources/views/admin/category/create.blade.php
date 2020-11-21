@extends('layouts.app')
@section('title',' Create New')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.sidebar')
            <div class="panel panel-default col-md-9">

                <div class="panel-heading">
                    <h4 class="title" style="font-weight: bold;"> {{$createvar['title']}}

                        <button type="button" class="btn btn-primary pull-right"
                                onclick="window.location='{{ route($route['index']) }}'">Cancel
                        </button>
                    </h4>
                    <p class="category">Items marked <sup class="required">*</sup> are required.</p>
                </div>

                <div class="panel-body">
                    <h4>
                        {!! Form::open(array('route' => $route['store'], 'class' => 'form-horizontal', 'data-parsley-validate' => '', 'autocomplete' => 'off', 'files' => true)) !!}

                        @foreach($fields as $field => $fv)
                            <div class="form-group{{ $errors->has($fv['name']) ? ' has-error' : '' }}">
                                <label class="{{$fv['label_length']}} control-label"
                                       for="NEW_subject">{{$fv['label']}} : <sup class="required">*</sup>
                                </label>
                                <div class="{{$fv['field_length']}} col-auto">

                                    <div class="input-group">
	                                    <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="{{$fv['field_icon']}}"></i></div>
	                                    </div>
                                        @if(!strcmp($fv['type'], "text"))
                                            {!! Form::text($fv['name'], $fv['default'], $fv['extras']) !!}
                                        @elseif(!strcmp($fv['type'], "textarea"))
                                            {!! Form::textarea($fv['name'], $fv['default'], $fv['extras']) !!}
                                        @elseif(!strcmp($fv['type'], "select"))
                                            {!! Form::select($fv['name'], $fv['choices'], $fv['default'], $fv['extras']) !!}
                                        @elseif(!strcmp($fv['type'], "checkbox"))
                                            {!! Form::checkbox($fv['name'], $fv['default'], $fv['checked'],$fv['extras']) !!}
                                        @elseif(!strcmp($fv['type'], "radio"))
                                            {!! Form::radio($fv['name'], $fv['default'], $fv['checked'],$fv['extras']) !!}
                                        @elseif(!strcmp($fv['type'], "file"))
                                            {!! Form::file($fv['name'],$fv['extras']) !!}
                                        @else

                                        @endif
                                        @if ($errors->has($fv['name']))
                                            <span class="help-block">
	                                            <strong>{{ $errors->first($fv['name']) }}</strong>
	                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="form-group">
                            {!! Form::submit($createvar['title'], array('class' => 'btn pull-down btn-success btn-lg col-lg-10 col-md-offset-2 col-xs-offset-3 text-center', 'id' => 'submit'  )) !!}
                        </div>
                        {!! Form::close() !!}
                    </h4>
                </div>
            </div>
        </div>
    </div>
@endsection
