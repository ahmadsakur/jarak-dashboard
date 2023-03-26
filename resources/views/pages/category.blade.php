@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Category'])
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
                        <h5>Category Table</h5>
                        <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                            data-bs-target="#insertCategoryModal"><i class="fa fa-plus" aria-hidden="true"></i> Insert</a>
                    </div>
                    <div class="p-4">
                        <table class="table align-items-center mb-0" id="categoryDatatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Description
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Todo : Render from table --}}
                                @forelse ($categories as $key => $category)
                                    <tr>
                                        <td class="text-xs font-weight-bold mb-0 align-top">
                                            {{ $key + 1 }}
                                        </td>
                                        <td class="align-top">
                                            <p class="text-xs font-weight-bold mb-0 align-top">{{ $category->name }}</p>
                                        </td>
                                        <td class="align-top">
                                            <p class="text-xs mb-0 text-wrap">{{ $category->description }}</p>
                                        </td>
                                        <td class="d-flex gap-3 justify-content-start align-items-center align-top">
                                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                                data-bs-target="#updateCategoryModal" id="editCategoryButton"
                                                data-edit="{{ $category->id }}">Edit</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteCategoryModal" id="deleteCategoryButton"
                                                data-destroy="{{ $category->id }}">Delete</button>
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
        {{-- Insert Modal --}}
        <div class="modal fade" id="insertCategoryModal" tabindex="-1" role="dialog" aria-labelledby="insertCategoryModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" method="POST" action="{{ route('category.store') }}" id="categoryInsertForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    aria-describedby="slugHelp" name="name" autocomplete="off" required
                                    placeholder="eg, coffee">
                            </div>
                            <div class="form-floating">
                                <textarea class="form-control" name="description" placeholder="insert category description" id="description"
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
        <div class="modal fade" id="updateCategoryModal" tabindex="-1" role="dialog" aria-labelledby="updateCategoryModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" action="{{ route('category.update', 'update') }}" method="POST">
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
                            <div class="form-floating">
                                <textarea class="form-control" name="description" placeholder="insert category description" id="edit-description"
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
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteCategoryModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form role="form" action="{{ route('category.destroy', 'category') }}" method="POST">
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
        // Edit category modal
        $(document).on('click', 'button#editCategoryButton', function() {
            let id = $(this).data('edit');
            $.ajax({
                type: "get",
                url: 'category/' + id,
                dataType: 'json',
                success: function(res) {
                    $('#edit-id').val(res[0].id);
                    $('#edit-name').val(res[0].name);
                    $('#edit-description').val(res[0].description);
                }
            })
        });

        // Delete category modal
        $(document).on('click', 'button#deleteCategoryButton', function() {
            let deleteID = $(this).data('destroy');
            $('#delete-id').val(deleteID);
        });
    </script>
@endpush
