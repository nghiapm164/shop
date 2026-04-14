@extends('layouts.app')

@section('meta_title', 'Cửa hàng - SportWear Shop')
@section('meta_description', 'Khám phá toàn bộ sản phẩm thể thao nam với bộ lọc theo danh mục, thương hiệu, giá, màu và kích cỡ.')

@section('content')
    <livewire:product-filter />
@endsection
