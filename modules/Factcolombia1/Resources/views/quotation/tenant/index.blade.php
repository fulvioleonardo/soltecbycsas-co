@extends('layouts.app')

@section('content')
    <tenant-quotation-quotation api="{{ env('SERVICE_FACT', '') }}" route="{{route('tenant.quotation')}}"></tenant-quotation-quotation>
@endsection
