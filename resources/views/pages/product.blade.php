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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Description
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Image</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Sold Out Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $key => $product)
                                    <tr>
                                        <td class="text-xs font-weight-bold mb-0 align-top">{{ $key + 1 }}</td>
                                        <td class="text-xs font-weight-bold mb-0 align-top">{{ $product->name }}</td>

                                        <td class="text-xs mb-0 text-wrap">
                                            {{ $product->description }}
                                        </td>
                                        <td class="align-top text-center text-sm">
                                            <button type="button" class="btn btn-sm text-xs font-weight-bold mb-0"
                                                data-id={{ $product->id }} id="showThumbnailButton">
                                                View Image
                                            </button>
                                        </td>
                                        <td class="align-top text-center text-sm">
                                            @if($product->isSoldOut == 1)
                                                <span class="badge bg-gradient-secondary">Sold</span>
                                            @else
                                                <span class="badge bg-gradient-success">Available</span>
                                            @endif
                                        </td>
                                        <td class="d-flex gap-3 justify-content-center align-items-center ">
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                data-bs-target="#updateProductModal" id="editProductButton"
                                                data-edit="{{ $product->id }}">Edit</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteProductModal" id="deleteProductButton"
                                                data-destroy="{{ $product->id }}">Delete</button>
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

        <!-- Show Image Modal -->
        <div class="modal fade" id="showImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="thumbnailTitle">#</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="w-100">
                            <img id="thumbnailSource" src="#" alt="#" width="100%" height="auto" />

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- DeleteModal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" action="{{ route('product.destroy', 'product') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <div class="modal-body">
                            <p>Deleting product will also delete product variant associated with this product</p>
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

        <!-- Update Product Modal -->
        <div class="modal fade" id="updateProductModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action="{{ route('product.update', 'update') }}"
                        enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit-id">
                            <div class="mb-3">
                                <div class="mb-1">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="edit-name" name="name"
                                        autocomplete="off" required </div>
                                </div>
                                <div class="mb-1">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" placeholder="insert product description" id="edit-description"
                                        style="height: 100px" required autocomplete="off"></textarea>
                                </div>
                                <div class="mb-1">
                                    <label for="category">Category</label>
                                    <select id="edit-category" class="form-select" aria-label=".form-select-sm example"
                                        name="category">
                                        <option selected value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="isSold">Sold Out Status</label>
                                    <div class="form-check form-switch mb-1">
                                        <input type="hidden" name="isSold" value="0" />
                                        <input class="form-check-input" type="checkbox" id="edit-isSold" name="isSold" value='1'>
                                        <label class="form-check-label" for="isSold" id="isSoldLabel"></label>
                                    </div>
                                </div>
                                <div class="mb-1">
                                    <div class="d-flex gap-3">
                                        <div class="w-100">
                                            <label>Thumbnail</label>
                                            <div class="rounded border">
                                                <img id="thumbnail-preview" src="" alt=""
                                                    style="width: 100%; height:auto">
                                            </div>
                                        </div>
                                        <div>
                                            <label for="thumbnail" class="form-label">Replace Image</label>
                                            <input class="d-block" type="file" name="thumbnail" id="thumbnail">
                                        </div>
                                    </div>
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

        <!-- Insert Product Modal -->
        <div class="modal fade" id="insertProductModal" tabindex="-1" role="dialog"
            aria-labelledby="insertProductModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action="{{ route('product.store') }}" id="productInsertForm"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="mb-1">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        autocomplete="off" required </div>
                                </div>
                                <div class="mb-1">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" placeholder="insert product description" id="description"
                                        style="height: 100px" required autocomplete="off"></textarea>
                                </div>
                                <div class="mb-1">
                                    <label for="category">Category</label>
                                    <select class="form-select" aria-label=".form-select-sm example" name="category">
                                        <option selected>Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-1">
                                    <label for="thumbnail" class="form-label">Image</label>
                                    <input class="d-block" type="file" name="thumbnail" id="thumbnail" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-primary"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Insert</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Show Thumbnail
        $(document).on('click', 'button#showThumbnailButton', function() {
            let id = $(this).data('id');
            $.ajax({
                type: "get",
                url: 'product/' + id,
                dataType: 'json',
                success: function(res) {
                    $('#thumbnailTitle').text(res[0].name)
                    $('#thumbnailSource').attr('src', res[0].imageUrl)
                    $('#thumbnailSource').attr('alt', res[0].name)
                    $('#showImageModal').modal('show')
                }

            }).done()

        });


        // Edit product modal
        $(document).on('click', 'button#editProductButton', function() {
            let id = $(this).data('edit');
            let isSoldLabel = $('#isSoldLabel');
            let isSoldCheckbox = $('#edit-isSold');
            $.ajax({
                type: "get",
                url: 'product/' + id,
                dataType: 'json',
                success: function(res) {
                    $('#edit-id').val(res[0].id);
                    $('#edit-name').val(res[0].name);
                    $('#edit-description').val(res[0].description);
                    $('#edit-category option').filter(function() {
                        return ($(this).val() == res[0].category_id);
                    }).prop('selected', true);
                    $('#edit-isSold').prop('checked', res[0].isSoldOut);
                    $('#thumbnail-preview').attr('src', res[0].imageUrl)
                    $('#thumbnail-preview').attr('alt', res[0].name)
                    isSoldLabel.text(res[0].isSoldOut === 1 || "1" || true ? 'Sold' : 'Available')

                }

            })

            $(isSoldCheckbox).change(function() {
                if ($(this).is(':checked')) {
                    $(this).next('label').text('Sold');
                } else {
                    $(this).next('label').text('Available');
                }
            });

        });


        // Delete product modal
        $(document).on('click', 'button#deleteProductButton', function() {
            let deleteID = $(this).data('destroy');
            $('#delete-id').val(deleteID);
        });
    </script>
@endpush
