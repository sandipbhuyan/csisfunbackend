@extends('layouts.app')

@section('title','Show Item')

@section('content')
    <div class="container">
        <div class="row">
            @include('partials.sidebar')
            <div class="row col-lg-9">
                <div class="col-md-8">

                    <div class="panel-heading">
                        <h3 class="title"><strong>{{$showvar['title']}}</strong></h3>
                        <hr>
                    </div>
                    <div class="panel-body">
                        <table class="table table-hover table-bordered">
                            <tbody>
                            <tr>
                                <th>Sl. No.</th>
                                <th>Table Attributes</th>
                                <th colspan="2">Table Values</th>
                            </tr>
                            @foreach($fields as $field => $fv)
                                {{-- STRCMP RETURNS 0 WHEN EQUAL --}}
                                <tr>
                                    <td>{{$loop->index + 1}}</td>
                                    <td>{{ucwords($fv['label'])}}</td>
                                    @if(!strcmp($field,"image"))

                                        <td><img src="{{url(${$singlepostvar}->$field)}}" width="200" height="150"></td>

                                    @elseif(strpos($field,"file"))
                                        <td><a href="{{url(${$singlepostvar}->$field)}}">DOWNLOAD FILE</a></td>


                                    @elseif(!strcmp($field,"id"))
                                        <td>{{ ${$singlepostvar}->$field }}</td>

                                    @elseif(!strcmp($field,"created_at"))
                                        <td>{{ date('d M Y, H:i:s', strtotime(${$singlepostvar}->$field) )}}</td>

                                    @elseif(!strcmp($field,"updated_at"))
                                        <td>{{ date('d M Y, H:i:s', strtotime(${$singlepostvar}->$field) )}}</td>

                                    @elseif(!strcmp($field,"is_published"))


                                    @else
                                        <td>{{ ${$singlepostvar}->$field }}</td>

                                    @endif

                                </tr>
                            @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ucwords(strtolower($showvar['title'].' Settings'))}}</h5>
                        </div>
                        <div class="card-body">
                            <h4>
                                <div class="well">
                                    <p>
                                        <b>Created at:</b>
                                        {{ date('M j, Y H:iA', strtotime(${$singlepostvar}->created_at)) }}
                                    </p>
                                    <br/>
                                    <p>
                                        <b>Updated at:</b>
                                        {{ date('M j, Y H:iA', strtotime(${$singlepostvar}->updated_at)) }}
                                    </p><br/>
                                </div>

                                <div>

                                    <a class="action" href="{{ route($route['edit'], ${$singlepostvar}->id) }}">
                                        <button class="btn btn-primary btn-block"><i class="fa fa-pencil"></i> Edit
                                        </button>
                                    </a>
                                    <hr/>
                                    {!! Form::open(['route' => [$route['destroy'], ${$singlepostvar}->id], 'method' =>'DELETE', 'style' => 'margin-top: -15px;']) !!}
                                    <button class="btn btn-danger btn-block"><i class="fa fa-close"></i> Delete</button>
                                    {!! Form::close() !!}
                                    <hr/>
                                    <a class="action" href="{{ route($route['index']) }}">
                                        <button style="margin-top:-15px;" class="btn btn-default btn-block"><i
                                                class="fa fa-book"></i> {{$showvar['seeall']}}</button>
                                    </a>
                                    <hr/>
                                    <a class="action" href="{{route('branch.show',${$singlepostvar}->branch_aid)}}">
                                        <button style="margin-top:-15px;" class="btn btn-default btn-block"><i
                                                class="fa fa-eye"></i> SHOW IN BRANCH
                                        </button>
                                    </a>
                                </div>
                            </h4>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
