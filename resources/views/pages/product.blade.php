@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Products'])
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
                        <h5>Product Table</h5>
                        <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#insertProductModal"><i class="fa fa-plus" aria-hidden="true"></i> Insert</a>
                    </div>
                    <div class="p-4">
                        <table class="table align-items-center mb-0" id="productDatatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Author
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Function
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Technology</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Employed</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Todo : Render from table --}}
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="https://demos.creative-tim.com/soft-ui-design-system-pro/assets/img/team-2.jpg"
                                                    class="avatar avatar-sm me-3">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-xs">John Michael</h6>
                                                <p class="text-xs text-secondary mb-0">john@creative-tim.com</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">Manager</p>
                                        <p class="text-xs text-secondary mb-0">Organization</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm badge-success">Online</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs"
                                            data-toggle="tooltip" data-original-title="Edit user">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- Insert Modal --}}
        <div class="modal fade" id="insertProductModal" tabindex="-1" role="dialog" aria-labelledby="insertProductModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action="{{ route('product.store') }}" id="productInsertForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    aria-describedby="slugHelp" name="name" autocomplete="off" required
                                    placeholder="eg, coffee">
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" name="description" placeholder="insert product description" id="description"
                                    style="height: 100px" required autocomplete="off"></textarea>
                                <label for="description">Description</label>
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
        <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog" aria-labelledby="updateProductModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" action="{{ route('product.update', 'update') }}" method="POST">
                        @method('PATCH')
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="edit-name" name="name"
                                    aria-describedby="slugHelp" name="name" autocomplete="off" required
                                    placeholder="eg, coffee">
                            </div>
                            <div class="mb-3">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" disabled readonly class="form-control" id="edit-slug"
                                    name="slug" aria-describedby="slugHelp" name="slug" autocomplete="off"
                                    required placeholder="eg, coffee">
                                <span class="text-xxs text-info">slug will be automatically generated</span>
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" name="description" placeholder="insert product description" id="edit-description"
                                    style="height: 100px" required autocomplete="off"></textarea>
                                <label for="description">Description</label>
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
        <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteProductModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" action="{{ route('product.destroy', 'product') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-body">
                            <p>Anda Ingin Menghapus Data ?</p>
                            <input type="hidden" name="id" id="delete-id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Edit product modal
        $(document).on('click', 'button#editProductButton', function() {
            let id = $(this).data('edit');
            $.ajax({
                type: "get",
                url: 'product/' + id,
                dataType: 'json',
                success: function(res) {
                    console.log(res);

                    $('#edit-id').val(res[0].id);
                    $('#edit-name').val(res[0].name);
                    $('#edit-slug').val(res[0].slug);
                    $('#edit-description').val(res[0].description);

                }

            })

        });


        // Delete product modal
        $(document).on('click', 'button#deleteProductButton', function() {
            let deleteID = $(this).data('destroy');
            $('#delete-id').val(deleteID);
        });
    </script>
@endpush
