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
                        <table class="table align-items-center mb-0" id="transactionDatatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Transaction ID</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Name</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Phone</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Table Number</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Amount</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Payment Method</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Payment Status</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Transaction Status</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Todo : Render from table --}}
                                @forelse ($transactions as $key => $transaction)
                                    <tr>
                                        <td class="text-sm font-weight-bold align-top">
                                            {{ $transaction->transaction_id }}
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-sm text-dark">{{ $transaction->customer_name }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-sm text-dark">{{ $transaction->customer_phone }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-sm text-dark text-center">{{ $transaction->table_number }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-sm text-dark">{{ formatCurrency($transaction->total_price) }}</p>
                                        </td>
                                        <td class=" mb-0 align-top">
                                            <p class="text-sm text-dark">{{ $transaction->payment_method }}</p>
                                        </td>
                                        <td class=" mb-0 align-top text-center">
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
                                        <td class=" mb-0 align-top">
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


                                        <td class="d-flex gap-3 justify-content-start align-items-center ">
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                data-bs-target="#updateCategoryModal" id="editCategoryButton"
                                                data-edit="{{ $transaction->id }}">Edit</button>
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
    @endsection
