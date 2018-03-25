@extends('layouts.registration')

@section('main-content')
<div class="bg-danger padding-10 margin-top30 text-center">
    @if ($exception->getMessage())
    <h4><strong>{{ $exception->getMessage() }}</strong></h4>
    @else
    <h4><strong>Oops!</strong> Something went wrong. Please check your raffle URL.</h4>
    @endif
</div>
@endsection
