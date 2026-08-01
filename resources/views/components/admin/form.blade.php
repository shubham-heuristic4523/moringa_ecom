@extends('layouts.app')

@section('title', 'Add Product')

@section('content')

<div class="admin-card">

    <div class="card-header">
        <h2>Add Product</h2>
        <p>Create a new product.</p>
    </div>

    <div class="card-body">

        <form action="" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="name">Product Name</label>

                <input
                    type="text"
                    name="name"
                    id="name"
                    class="form-input"
                    placeholder="Enter product name"
                >
            </div>

            <div class="form-group">
                <label for="description">Description</label>

                <textarea
                    name="description"
                    id="description"
                    class="form-textarea"
                    rows="5"
                ></textarea>
            </div>

            <div class="form-group">
                <label for="price">Price</label>

                <input
                    type="number"
                    name="price"
                    id="price"
                    class="form-input"
                    step="0.01"
                >
            </div>

            <div class="form-group">
                <label for="stock">Stock</label>

                <input
                    type="number"
                    name="stock"
                    id="stock"
                    class="form-input"
                >
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>

                
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>

                <input
                    type="file"
                    name="image"
                    id="image"
                    class="form-file"
                >
            </div>

            <div class="form-actions">
                <a href="" class="btn btn-secondary">
                    Cancel
                </a>

                <button type="submit" class="btn btn-primary">
                    Save Product
                </button>
            </div>

        </form>

    </div>

</div>

@endsection