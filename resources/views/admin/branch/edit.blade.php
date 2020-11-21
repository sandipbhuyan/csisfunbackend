@extends('layouts.app')

@section('title','UPDATE ALBUM | EDIT')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.sidebar')

            <div class="col-lg-9">

                <div class="content" >
                    <!-- Content goes here -->
                    <fieldset>
                        {!! Form::model(${$singlepostvar}, ['route' => [$route['update'], ${$singlepostvar}->id], 'class' => 'form-horizontal', 'method' => 'PUT', 'data-parsley-validate' => '', 'autocomplete' => 'off', 'files'=> true]) !!}

                        <div class="col-md-8">


                            <div class="content-box-header">
                                <div class="panel-title"><h3><b>EDIT ALBUM</b></h3></div>
                            </div>

                            <div class="content-box-large box-with-header">
                                @foreach($fields as $field => $fv)
                                    <div class="form-group{{ $errors->has($fv['name']) ? ' has-error' : '' }}">
                                        <label class="{{$fv['label_length']}} control-label"
                                               for="NEW_subject">{{$fv['label']}} : <sup class="required">*</sup>
                                        </label>
                                        <div class="{{$fv['field_length']}}">

                                            <div class="input-group border-input">
		                                                <span class="input-group-addon">
		                                                    <i class="{{$fv['field_icon']}}"></i>
		                                                </span>
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

                                                    <img src="{{url(${$singlepostvar}->$field)}}" width="250" height="150"><br>
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


                            </div>
                        </div>

                        <div class="col-md-4">

                            <div class="content-box-header">
                                <div class="panel-title"><h3><b>STATUS</b></h3></div>
                            </div>
                            <div class="content-box-large box-with-header">

                                <div class="well">
                                    <p><b>Created at :</b>{{ date('M j, Y H:ia', strtotime(${$singlepostvar}->created_at)) }}</p><br/>
                                    <p><b>Updated at :</b>{{ date('M j, Y H:ia', strtotime(${$singlepostvar}->updated_at)) }}</p><br/>

                                </div>

                                <div>
                                    {!! Html::linkRoute($route['index'], 'Cancel', array(${$singlepostvar}->id), array('class' =>'btn btn-primary btn-block')) !!}

                                    {!! Form::submit('Update', array('class' => 'btn btn-success btn-block', 'id' => 'submit'  ))  !!}
                                </div>

                            </div>
                        </div>
                        {!! Form::close() !!}
                    </fieldset>


                </div>
            </div>
        </div>
    </div>



@endsection
