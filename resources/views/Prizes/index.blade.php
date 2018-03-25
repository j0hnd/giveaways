@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" href="{{ url('/css/cmxform.css') }}">
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="pull-left">Prizes</h1>
        </div>
        <div class="col-md-6 text-right padding-top17">
            <form id="search-form" class="form-inline">
                <button type="button" id="toggle-add-prize" class="btn btn-primary margin-right20">
                    <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> New Price
                </button>
                <div class="form-group">
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search here...">
                </div>
                <button type="button" id="toggle-prize-search" class="btn btn-default">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th class="col-md-4">Item Name</th>
                    <th class="col-md-2 text-right">Amount (US$)</th>
                    <th class="col-md-2 text-center">Date Added</th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="prize-list-container">
                @include('Partials.Prizes._list', compact('prizes'))
                </tbody>
                @if (count($prizes))
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">{{ $prizes->links() }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection

@section('custom-js')
<script src="{{ url('/js/class/prizes.js') }}" type="text/javascript"></script>
@endsection
