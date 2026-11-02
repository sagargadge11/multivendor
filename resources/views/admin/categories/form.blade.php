@extends('layouts.admin')
@section('title', $category->exists ? 'Edit Category' : 'Add Category')
@section('content')
<div class="mb-4"><a href="{{ route('admin.categories.index') }}" class="text-decoration-none">&larr; Back to categories</a><h3 class="mt-2">{{ $category->exists ? 'Edit Global Category' : 'Add Global Category' }}</h3></div>
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
<form method="POST" action="{{ $category->exists ? route('admin.categories.update',$category) : route('admin.categories.store') }}">
@csrf @if($category->exists) @method('PUT') @endif
<div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Category Name *</label><input name="name" class="form-control" value="{{ old('name',$category->name) }}" required></div>
<div class="col-md-6"><label class="form-label">URL Slug</label><input name="slug" class="form-control" value="{{ old('slug',$category->slug) }}" placeholder="Auto-generated if blank"></div>
<div class="col-md-6"><label class="form-label">Parent Category</label><select name="parent_id" class="form-select"><option value="">No parent (Main category)</option>@foreach($categories as $parent)<option value="{{ $parent->id }}" @selected((string)old('parent_id',$category->parent_id)==(string)$parent->id)>{{ $parent->name }}</option>@endforeach</select><small class="text-muted">Choose a parent to create a subcategory.</small></div>
<div class="col-md-6"><label class="form-label">Status</label><div class="form-check mt-2"><input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status',$category->exists ? $category->status : true))><label for="status" class="form-check-label">Active</label></div></div>
<div class="col-12"><label class="form-label">Category Description</label><textarea name="description" rows="3" class="form-control">{{ old('description',$category->description) }}</textarea></div>
</div></div></div>
<div class="card border-0 shadow-sm mb-3"><div class="card-body p-4">
<h5>SEO Settings</h5><p class="text-muted small">Leave fields blank to use automatically generated defaults.</p>
<div class="mb-3"><label class="form-label">SEO Title</label><input name="seo_title" maxlength="255" class="form-control" value="{{ old('seo_title',$category->seo_title) }}"></div>
<div class="mb-3"><label class="form-label">Meta Description</label><textarea name="seo_description" maxlength="500" rows="3" class="form-control">{{ old('seo_description',$category->seo_description) }}</textarea></div>
<div class="mb-3"><label class="form-label">Meta Keywords (optional)</label><input name="seo_keywords" class="form-control" value="{{ old('seo_keywords',$category->seo_keywords) }}"></div>
<div><label class="form-label">Robots</label><select name="meta_robots" class="form-select">@foreach(['index,follow','noindex,follow','noindex,nofollow'] as $robots)<option value="{{ $robots }}" @selected(old('meta_robots',$category->meta_robots ?? 'index,follow')===$robots)>{{ $robots }}</option>@endforeach</select></div>
</div></div>
<button class="btn btn-primary px-4">Save Category</button>
</form>
@endsection
