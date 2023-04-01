@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Transaction List'])
    <div class="container-fluid py-4">
        @if (isset($errors) && $errors->any())
            <div class="alert border border-white alert-dismissible fade show text-white" role="alert">
                @foreach ($errors->all() as $error)
                    <span class="alert-icon"><i class="fa fa-warning"></i></span>
                    <span class="alert-text"><strong>{{ $error }}</strong></span>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">

                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h5>Transaction Table</h5>
                        <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal"
                            data-bs-target="#insertCategoryModal"><i class="fa fa-plus" aria-hidden="true"></i> External
                            Link</a>
                    </div>
                    <div class="p-4">
                        <table class="table align-items-center mb-0" id="transactionDatatable" style="width: 100%">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Transaction ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Table
                                        Number</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment
                                        Method</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Payment
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Transaction Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Todo : Render from table --}}
                                @forelse ($transactions as $key => $transaction)
                                    <tr class="align-items-start">
                                        <td class="text-xs font-weight-bold mb-0 align-top">
                                            {{ $transaction->transaction_id }}
                                        </td>
                                        <td class="mb-0 align-top">
                                            <p class="text-xs font-weight-bold mb-0 align-top">
                                                {{ $transaction->customer_name }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-xs font-weight-bold mb-0 align-top">
                                                {{ $transaction->customer_phone }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-xs font-weight-bold mb-0 align-top text-center">
                                                {{ $transaction->table_number }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-xs font-weight-bold mb-0 align-top">
                                                {{ formatCurrency($transaction->total_price) }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-xs font-weight-bold mb-0 align-top">
                                                {{ $transaction->payment_method }}</p>
                                        </td>
                                        <td class="text-xs font-weight-bold mb-0 align-top">
                                            @switch($transaction->payment_status)
                                                @case('UNPAID')
                                                    <span class="badge badge-sm bg-secondary">UNPAID</span>
                                                @break

                                                @case('PAID')
                                                    <span class="badge badge-sm bg-success">PAID</span>
                                                @break

                                                @case('EXPIRED')
                                                    <span class="badge badge-sm bg-dark">EXPIRED</span>
                                                @break

                                                @case('FAILED')
                                                    <span class="badge badge-sm bg-danger">FAILED</span>
                                                @break

                                                @case('REFUND')
                                                    <span class="badge badge-sm bg-info">REFUND</span>
                                                @break

                                                @default
                                                    <span class="badge badge-sm bg-secondary">UNPAID</span>
                                            @endswitch

                                        </td>
                                        <td class="text-xs font-weight-bold mb-0 align-top">
                                            @switch($transaction->transaction_status)
                                                @case('INITIAL')
                                                    <span class="badge badge-sm bg-secondary">Menunggu Pembayaran</span>
                                                @break

                                                @case('CONFIRMED')
                                                    <span class="badge badge-sm bg-info">Dibayar</span>
                                                @break

                                                @case('PROCESSED')
                                                    <span class="badge badge-sm bg-info">Dalam Proses</span>
                                                @break

                                                @case('COMPLETED')
                                                    <span class="badge badge-sm bg-success">Selesai</span>
                                                @break

                                                @case('CANCELLED')
                                                    <span class="badge badge-sm bg-danger">Dibatalkan</span>
                                                @break

                                                @default
                                                    <span class="badge badge-sm bg-secondary">Menunggu Pembayaran</span>
                                            @endswitch
                                        </td>


                                        <td class="text-xs font-weight-bold mb-0 align-top">
                                            <button class="btn sm btn-outline-info text-xs" data-bs-toggle="modal"
                                                data-bs-target="#transactionDetailModal" id="transactionModalButton"
                                                data-edit="{{ $transaction->id }}">Details</button>
                                        </td>
                                    </tr>
                                    @empty
                                        <div></div>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal --}}
        <div class="modal fade" id="transactionDetailModal" tabindex="-1" role="dialog"
            aria-labelledby="transactionDetailModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="transactionDetailModalTitle">Transaction Detail</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Should this be a modal ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </div>
        @endsection
