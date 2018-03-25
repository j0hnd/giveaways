@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" href="{{ url('/css/cmxform.css') }}">
@endsection

@section('main-content')
<div class="row">
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">
            <div class="row">
                {{-- left panel --}}
                <div class="col-md-6"><h1>Raffles</h1></div>

                {{-- right panel --}}
                <div class="col-md-6 text-right padding-top17">
                    <form id="search-form" class="form-inline">
                        <label class="checkbox-inline margin-right30">
                            <input type="checkbox" id="toggle-auto-draw" {{ $toggle_auto_draw }} data-toggle="toggle">
                            Auto Draw
                        </label>
                        <button type="button" id="toggle-create-raffle" class="btn btn-primary margin-right20">
                            <span class="glyphicon glyphicon-modal-window" aria-hidden="true"></span>
                            New Raffle
                        </button>
                        <div class="form-group">
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search here...">
                        </div>
                        <button type="button" id="toggle-raffle-search" class="btn btn-default">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                @include('Partials.Raffles._callouts', ['stats' => $stats])
            </div>

            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
                eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>

        <!-- Table -->
        <table class="table">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th class="col-md-3">Raffle Name</th>
                    <th class="col-md-4">Raffle URL</th>
                    <th class="col-md-2">Duration</th>
                    <th class="col-md-3">&nbsp;</th>
                </tr>
                </thead>

                <tbody id="raffle-list-container">
                @include('Partials.Raffles._list', compact('raffles'))
                </tbody>

                @if (count($raffles))
                    <tfoot>
                    <tr><td colspan="4" class="text-right">{{ $raffles['object']->links() }}</td></tr>
                    </tfoot>
                @endif
            </table>
        </table>

        <div class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-center">
                    @include('Partials.Raffles._legends')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom-js')
<script src="{{ url('/js/class/raffles.js') }}" type="text/javascript"></script>
@endsection
