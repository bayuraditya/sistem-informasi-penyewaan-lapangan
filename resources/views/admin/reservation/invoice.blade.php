@extends('layouts.checkout-app')

@section('content')
    <div class="container" style="margin-top: 100px; margin-bottom: 100px;">
        <h1 class="mb-4">Invoice</h1>

        <div class="card" style="width: 50rem">
            <h5 class="card-title text-light text-center bg-primary pb-2">Invoice Details</h5>
            <div class="card-body">
                <div class="detail-invoice p-1">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Transaction ID:</strong>
                        </div>
                        <div class="col-md-6">
                            {{ $transaction->id }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Payment Status:</strong>
                        </div>
                        <div class="col-md-6">
                            {{ $transaction->payment_status }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>User Name:</strong>
                        </div>
                        <div class="col-md-6">
                            {{ $transaction->user->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Email:</strong>
                        </div>
                        <div class="col-md-6">
                            {{ $transaction->user->email }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <strong>Transaction Date:</strong>
                        </div>
                        <div class="col-md-6">
                            {{ $transaction->created_at->format('d-m-Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary">Home</a>
        </div>
    </div>
@endsection
