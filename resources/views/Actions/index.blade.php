@extends('layouts.app')

@section('custom-css')
<link rel="stylesheet" href="{{ url('/css/cmxform.css') }}">
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-6">
            <h1 class="pull-left">Actions</h1>
        </div>
        <div class="col-md-6 text-right padding-top17">
            <button type="button" id="toggle-add-action" class="btn btn-primary">
                <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> New Action
            </button>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-md-4">Action Name</th>
                        <th class="col-md-1">Def.</th>
                        <th class="col-md-2 text-center">Date Added</th>
                        <th class="col-md-5"></th>
                    </tr>
                </thead>
                <tbody id="action-list-container">
                @include('Partials.Actions._list', compact('actions'))
                </tbody>
                @if (count($actions))
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">{{ $actions->links() }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    @include('Partials.Modals._actions')
@endsection

@section('custom-js')
<script src="{{ url('/js/class/actions.js') }}" type="text/javascript"></script>
@endsection
