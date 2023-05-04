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
                            data-bs-target="#insertCategoryModal">Tripay Dashboard
                        </a>
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
                                                data-detail="{{ $transaction->transaction_id }}">Details</button>
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
            {{-- Modal --}}
            <div class="modal fade" id="transactionDetailModal" tabindex="-1" role="dialog"
                aria-labelledby="transactionDetailModal" aria-hidden="true" style="min-width: 600px">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form role="form" action="{{ route('transaction.update', 'update') }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h5 class="modal-title" id="transactionDetailModalTitle">Transaction Details</h5>
                                <div class="dropdown text-sm" id="dropdown-status">
                                    <input type="hidden" name="transaction_id" id="transactionIdInput">
                                    <select class="form-select" name="status" id="transactionStatusSelect"
                                        aria-label="Default select example">
                                        <option value="INITIAL">Menunggu Pembayaran</option>
                                        <option value="CONFIRMED">Dibayar</option>
                                        <option value="PROCESSED">Dalam Proses</option>
                                        <option value="COMPLETED">Selesai</option>
                                        <option value="CANCELLED">Dibatalkan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered align-items-start mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Product Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Quantity</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Price</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Total Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="transactionDetailTable">
                                    </tbody>
                                </table>
                                {{-- notes --}}
                                <div id="notesContainer" class="mt-4 mb-2 ml-2">
                                    <p class="text-xxs font-weight-bold mb-1">
                                        Notes
                                    </p>
                                    <p class="text-sm opacity-9" id="notes-content"></p>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                            </div>
                    </form>

                </div>
            </div>
        </div>
        </div>
    @endsection
    @push('js')
        <script>
            // Show Product Detail
            function formatCurrencyToIDR(amount) {
                let formatter = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                });
                return formatter.format(amount);
            }
            $(document).on('click', 'button#transactionModalButton', function() {
                let id = $(this).data('detail');
                let url = "{{ route('transaction.detail', ':id') }}";
                url = url.replace(':id', id);
                $.ajax({
                    url: url,
                    method: "GET",
                    beforeSend: function() {
                        // Show loading animation
                        $('#transactionDetailTable').html(
                            `<tr>
                                <td colspan="4" class="text-center">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </td>
                            </tr>`
                        );

                        // set transaction status dropdown to hidden
                        // $('#dropdown-status').hide();
                    },
                    success: function(data) {
                        let tbody = $('#transactionDetailTable');
                        tbody.empty(); // Clear existing data
                        let notes = $('#notes-content');
                        $('#dropdown-status').show();


                        $.each(data.items, function(index, item) {
                            let row = $('<tr class="align-items-center">');
                            row.append($('<td class="text-xs font-weight-bold mb-0 align-top">')
                                .text(item.name));
                            row.append($(
                                '<td class="text-xs font-weight-bold mb-0 align-top text-center">'
                            ).text(item.quantity));
                            row.append($(
                                '<td class="text-xs font-weight-bold mb-0 align-top text-center">'
                            ).text(formatCurrencyToIDR(item.price)));
                            row.append($(
                                '<td class="text-xs font-weight-bold mb-0 align-top text-center">'
                            ).text(formatCurrencyToIDR(item.subtotal)));
                            tbody.append(row);
                        });

                        // Set transaction data
                        $('#transactionIdInput').val(id);
                        $('#transactionStatusSelect option').filter(function() {
                            return ($(this).val() == data.status);
                        }).prop('selected', true);

                        // Set notes
                        if (data.notes) {
                            notes.text(data.notes);
                        } else {
                            notes.text('No notes');
                        }

                    },
                    error: function(error) {
                        let tbody = $('#transactionDetailTable');
                        let dropdown = $('#dropdown-status');
                        let notes = $('#notes-content');


                        dropdown.hide();
                        notes.empty();
                        tbody.empty(); // Clear existing data
                        tbody.append(
                            `<tr>
                                <td colspan="4" class="text-center">
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Oops!</strong> Something went wrong.
                                    </div>
                                    <p class="text-xxs font-weight-bold">
                                        Check the data on the TriPay server
                                        </p>
                                    <button class="btn btn-outline-primary" data-bs-dismiss="modal">
                                        <a href="https://tripay.co.id/simulator/transaction/${id}" target="_blank">
                                        Order Details</button>
                                </td>
                            </tr>`
                        );
                    }
                });
            });
        </script>
    @endpush
