@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Projects') }}</div>

                <div class="card-body">
                    @if (count($projects))
                      <ol>
                        @foreach ($projects as $project)
                          <li>
                            <a href="{{ $project->path() }}">{{ $project->title }}</a>
                          </li>
                        @endforeach
                      </ol>
                    @else
                      <p>No projects here.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
