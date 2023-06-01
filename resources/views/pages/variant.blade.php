@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Variants'])
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
                        <h5>Variant Table</h5>
                        <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#insertVariantModal"><i class="fa fa-plus" aria-hidden="true"></i> Insert</a>
                    </div>
                    <div class="p-4">
                        <table class="table align-items-center mb-0" id="variantDatatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Product Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Variant Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Price</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($variants as $variant)
                                    <tr>
                                        <td class="text-xs font-weight-bold mb-0 align-middle">{{ $loop->iteration }}</td>
                                        <td class="text-xs font-weight-bold mb-0 align-middle">{{ $variant->product->name }}</td>
                                        <td class="text-xs font-weight-bold mb-0 align-middle">{{ $variant->variant_name }}</td>
                                        <td class="text-xs font-weight-bold mb-0 align-middle">{{ $variant->price }}</td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                data-bs-target="#updateVariantModal" id="editVariantButton"
                                                data-id="{{ $variant->id }}">Edit</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteVariantModal" id="deleteVariantButton"
                                                data-id="{{ $variant->id }}">Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Insert Modal --}}
        <div class="modal fade" id="insertVariantModal" tabindex="-1" role="dialog" aria-labelledby="insertVariantModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action="{{ route('variant.store') }}" id="variantInsertForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="product">Product</label>
                                <select id="product_id" class="form-select" name="product_id"
                                    aria-label=".form-select-sm example" name="product" required>
                                    <option value="" selected>Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    aria-describedby="slugHelp" name="name" autocomplete="off" required
                                    placeholder="ice, hot, extra egg">
                            </div>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <div class="position-relative">
                                    <input type="number" class="form-control" style="padding-left: 2.5rem;" step=""
                                        name="price" id="price" required autocomplete="off">
                                    </input>
                                    <span class="position-absolute text-sm font-weight-bold"
                                        style="top:9px; left:12px;">Rp</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Insert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Update Modal --}}
        <div class="modal fade" id="updateVariantModal" tabindex="-1" role="dialog"
            aria-labelledby="updateVariantModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action="{{ route('variant.update', 'update') }}"
                        id="variantUpdateForm">
                        @method('PATCH')
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="mb-3">
                                <label for="product">Product</label>
                                <select id="edit-product_id" class="form-select" name="product_id"
                                    aria-label=".form-select-sm example" name="product" required>
                                    <option value="" selected>Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit-name" name="name"
                                    aria-describedby="slugHelp" name="name" autocomplete="off" required
                                    placeholder="eg, coffee">
                            </div>
                            <div class="mb-3">
                                <label for="price">Price</label>
                                <div class="position-relative">
                                    <input type="number" class="form-control" style="padding-left: 2.5rem;"
                                        step="" name="price" id="edit-price" required autocomplete="off">
                                    </input>
                                    <span class="position-absolute text-sm font-weight-bold"
                                        style="top:9px; left:12px;">Rp</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div class="modal fade" id="deleteVariantModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteVariantModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" action="{{ route('variant.destroy', 'variant') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-body">
                            <p>Do You want to Delete this product variant ?</p>
                            <input type="hidden" name="id" id="delete-id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Edit variant modal
        $(document).on('click', 'button#editVariantButton', function() {
            let id = $(this).data('id');
            $.ajax({
                type: "get",
                url: 'variant/' + id,
                dataType: 'json',
                success: function(res) {
                    $('#edit-id').val(res[0].id);
                    $('#edit-product_id option').filter(function() {
                        return ($(this).val() == res[0].product_id);
                    }).prop('selected', true);
                    $('#edit-name').val(res[0].variant_name);
                    $('#edit-price').val(res[0].price);
                }
            })
        });


        // Delete variant modal
        $(document).on('click', 'button#deleteVariantButton', function() {
            let deleteID = $(this).data('id');
            $('#delete-id').val(deleteID);
        });
    </script>
@endpush
