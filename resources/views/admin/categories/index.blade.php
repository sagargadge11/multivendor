@extends('layouts.admin')
@section('title', 'Global Categories')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div><h3 class="mb-1">Global Categories</h3><p class="text-muted mb-0">Marketplace-wide categories shared by all vendors.</p></div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add Category</a>
</div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
<div class="card border-0 shadow-sm"><div class="table-responsive">
<table class="table align-middle mb-0">
<thead class="table-light"><tr><th>Category</th><th>Slug</th><th>Subcategories</th><th>Products</th><th>Status</th><th class="text-end">Action</th></tr></thead>
<tbody>
@forelse($categories as $category)
<tr>
<td><strong>{{ $category->parent?->name ? $category->parent->name.' / ' : '' }}{{ $category->name }}</strong></td>
<td><code>{{ $category->slug }}</code></td>
<td>{{ $category->children_count }}</td><td>{{ $category->products_count }}</td>
<td><span class="badge {{ $category->status ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $category->status ? 'Active' : 'Inactive' }}</span></td>
<td class="text-end">
<a href="{{ route('admin.categories.edit',$category) }}" class="btn btn-sm btn-outline-primary">Edit</a>
<form method="POST" action="{{ route('admin.categories.destroy',$category) }}" class="d-inline" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
</td>
</tr>
@empty
<tr><td colspan="6" class="text-center text-muted py-4">No categories created yet.</td></tr>
@endforelse
</tbody></table></div>
<div class="card-body">{{ $categories->links() }}</div></div>
@endsection
