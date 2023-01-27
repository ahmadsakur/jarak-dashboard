@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Category'])
    <div class="container-fluid py-4">
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
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">No</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Name</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark">Slug</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark w-50">Description</th>
                                    <th class="text-uppercase text-xs font-weight-bolder text-dark w-25">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Todo : Render from table --}}
                                @forelse ($categories as $key => $category)
                                    <tr>
                                        <td>
                                            {{ $key + 1 }}
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $category->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ $category->slug }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0 text-wrap">{{ $category->description }}</p>
                                        </td>
                                        <td class="d-flex gap-3 justify-content-start align-items-center ">
                                            <button class="btn btn-sm btn-outline-info">Edit</button>
                                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteCategoryModal">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" align="center">Categories not found, lets create one!</td>
                                    </tr>
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
                    <div class="modal-body">
                        <form role="form" method="POST" action="{{ route('category.store') }}" id="categoryInsertForm">
                            @csrf
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
                            {{-- <button type="submit">test</button> --}}
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="categoryInsertForm" class="btn btn-primary">Insert</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Update Modal --}}
        {{-- Delete Modal --}}
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModal"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirm Logout</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure want to delete category ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn bg-danger text-white">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
