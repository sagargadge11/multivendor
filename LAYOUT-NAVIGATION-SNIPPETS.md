# Sidebar navigation snippets

Merge these links into the existing sidebars. Do not replace the complete layout files.

## Admin sidebar

```blade
<a href="{{ route('admin.vendors.index') }}" class="nav-link">
    <i class="bi bi-shop me-2"></i>
    Vendors
</a>
```

## Vendor sidebar

```blade
<a href="{{ route('vendor.profile.edit') }}" class="nav-link">
    <i class="bi bi-building me-2"></i>
    Business Profile
</a>
```
