@extends('layouts.app')

@section('title' )
    {{$indexvar['title']}}
@endsection

@section('content')

    <div class="container">
        <div class="row">
            @include('partials.sidebar')
            <div class="panel col-lg-9">
                <div class="panel-heading">
                    <h4 class="title">{{$indexvar['title']}}
                        <a href="{{ route($route['create']) }}" class="btn btn-success pull-right">
                            <i class="fa fa-plus"></i> {{$indexvar['newitem']}}
                        </a>
                        <div >
                            <b>Total of {{$indexvar['title']}}:</b>
                            <span class="badge label-success">{{${$multipostvar}->total()}}</span>
                            <br/><br/>
                            <b>URL:</b>
                            <a target="_blank" href="{{ url($indexvar['url']) }}">{{ url($indexvar['url']) }}</a>
                        </div>
                    </h4>
                </div>
                <hr>

                <div class="panel-body" >
                    <h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    @foreach($fields as $field => $fv)
                                        <th>{{ucwords($fv['label'])}}</th>
                                    @endforeach
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach (${$multipostvar} as $notice)
                                    <tr>
                                        @foreach($fields as $field => $fv)
                                            {{-- STRCMP RETURNS 0 WHEN EQUAL --}}

                                            @if(!strcmp($field,"image"))

                                                <td><img src="{{url($notice->$field)}}" width="100" height="50"></td>


                                            @elseif(strpos($field,"file"))
                                                <td><a href="{{url($notice->$field)}}">DOWNLOAD FILE</a></td>

                                            @elseif(!strcmp($field,"created_at"))

                                                <td>{{ date('d M Y, H:i:s', strtotime($notice->$field) )}}</td>

                                            @elseif(!strcmp($field,"updated_at"))

                                                <td>{{ date('d M Y, H:i:s', strtotime($notice->$field) )}}</td>

                                            @elseif(!strcmp($field,"slug"))
                                                <td><a target="_blank" href="{{url($indexvar['urltomain'].$notice->$field)}}">GO TO ALBUM</a></td>

                                            @else
                                                <td>{{ substr($notice->$field,0,20) }}{{ strlen($notice->$field) > 20 ? "..." : ""}}</td>

                                            @endif
                                        @endforeach
                                        <td class="actions">
                                            @if(!$notice->is_published)
                                                <a href="{{ route($route['publish'], $notice->id)}}">
                                                    <button class="btn btn-sm btn-primary">
                                                        <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                    </button>
                                                </a>
                                            @else
                                                <a href="{{ route($route['unpublish'], $notice->id)}}">
                                                    <button class="btn btn-sm btn-danger">
                                                        <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                                                    </button>
                                                </a>
                                            @endif
                                            <a href="{{ route($route['show'], $notice->id)}}">
                                                <button class="btn btn-sm btn-primary">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </button>
                                            </a>
                                            <a href="{{ route($route['edit'], $notice->id) }}">
                                                <button class="btn btn-sm btn-warning">
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="text-center">
                            {!! ${$multipostvar}->render() !!}
                        </div>
                    </h5>
                </div>
            </div>
        </div>
    </div>

@endsection
