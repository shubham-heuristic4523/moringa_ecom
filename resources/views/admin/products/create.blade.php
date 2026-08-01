@extends('layouts.app')

@section('title', 'Add Product')
@section('page-title', 'Add Product')
@section('page-subtitle', 'Create a new product for your catalog.')

@section('content')
    <x-admin.form
        title="Product Details"
        subtitle="Name, image and description are required to start — more fields can be added later."
        action="#"
        :fields="[
            ['name' => 'name', 'label' => 'Product Name', 'required' => true, 'placeholder' => 'e.g. Moringa Powder 250g'],
            ['name' => 'image', 'label' => 'Product Image', 'type' => 'file', 'accept' => 'image/*', 'required' => true, 'width' => 'full'],
            ['name' => 'description', 'label' => 'Description', 'type' => 'textarea', 'width' => 'full', 'placeholder' => 'Short description of the product…'],
        ]"
        submit-label="Save Product"
        cancel-url="{{ url('/admin') }}"
    />
@endsection
