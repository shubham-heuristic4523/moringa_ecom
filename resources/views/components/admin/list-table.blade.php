@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div class="admin-card">

    <div class="card-header">

        <div>
            <h2>Products</h2>
            <p>All products in your catalog</p>
        </div>

        <div class="table-toolbar">

            <input
                type="text"
                id="search"
                placeholder="Search Product..."
                class="form-input">

            <a href="" class="btn btn-primary">
                + Add Product
            </a>

        </div>

    </div>

    <div class="table-scroll">

        <table class="admin-table" id="productTable">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th width="180">Action</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>1</td>
                    <td>Moringa Powder</td>
                    <td>Health Supplements</td>
                    <td>₹499.00</td>
                    <td>150</td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">View</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>

                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <td>2</td>
                    <td>Moringa Capsules</td>
                    <td>Capsules</td>
                    <td>₹699.00</td>
                    <td>80</td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">View</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>

                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <td>3</td>
                    <td>Moringa Tea</td>
                    <td>Tea</td>
                    <td>₹299.00</td>
                    <td>60</td>
                    <td>
                        <span class="badge bg-danger">Inactive</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">View</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>

                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <td>4</td>
                    <td>Moringa Oil</td>
                    <td>Skin Care</td>
                    <td>₹899.00</td>
                    <td>45</td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">View</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>

                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <tr>
                    <td>5</td>
                    <td>Moringa Face Pack</td>
                    <td>Beauty Products</td>
                    <td>₹399.00</td>
                    <td>120</td>
                    <td>
                        <span class="badge bg-success">Active</span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-info btn-sm">View</a>
                        <a href="#" class="btn btn-warning btn-sm">Edit</a>

                        <form action="#" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

<script>
    document.getElementById('search').addEventListener('keyup', function() {

        let value = this.value.toLowerCase();

        let rows = document.querySelectorAll('#productTable tbody tr');

        rows.forEach(function(row) {

            row.style.display =
                row.innerText.toLowerCase().includes(value) ?
                '' :
                'none';

        });

    });
</script>

@endsection