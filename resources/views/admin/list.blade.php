{{-- Demo page for <x-admin.list-table>. To reuse for another section (Orders, Customers…),
     only $demoColumns / $demoRows below change — the markup stays the same. --}}
@extends('layouts.app')

@section('title', 'List Demo')
@section('page-title', 'List Demo')
@section('page-subtitle', 'Reusable table — swap the column/row arrays to reuse for any section.')

@php
    $demoColumns = [
        ['key' => 'name', 'label' => 'Product'],
        ['key' => 'price', 'label' => 'Price', 'format' => fn ($v) => '$' . number_format($v, 2)],
        ['key' => 'quantity', 'label' => 'Stock'],
        ['key' => 'status', 'label' => 'Status', 'format' => fn ($v) => match ($v) {
            'active' => '<span class="badge badge-success">In Stock</span>',
            'low' => '<span class="badge badge-warning">Low Stock</span>',
            'out' => '<span class="badge badge-danger">Out of Stock</span>',
            default => '<span class="badge badge-neutral">' . e($v) . '</span>',
        }],
    ];

    $demoRows = [
        ['id' => 1, 'name' => 'Moringa Powder 250g', 'price' => 12.99, 'quantity' => 120, 'status' => 'active'],
        ['id' => 2, 'name' => 'Moringa Capsules 60ct', 'price' => 18.50, 'quantity' => 8, 'status' => 'low'],
        ['id' => 3, 'name' => 'Moringa Tea Bags 20ct', 'price' => 9.99, 'quantity' => 0, 'status' => 'out'],
        ['id' => 4, 'name' => 'Moringa Oil 100ml', 'price' => 22.00, 'quantity' => 45, 'status' => 'active'],
    ];
@endphp

@section('content')
    <x-admin.list-table
        title="Products"
        subtitle="Sample rows — swap `columns` + `rows` to reuse this table for Orders, Customers, etc."
        :columns="$demoColumns"
        :rows="$demoRows"
        add-url="{{ route('admin.form') }}"
        add-label="Add Product"
        :edit-route="fn ($row) => '#'"
        :delete-route="fn ($row) => '#'"
        empty-message="No products yet."
    />
@endsection
