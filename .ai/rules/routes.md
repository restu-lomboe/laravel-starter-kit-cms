---
paths:
  - routes/web.php
---

# Routes

## Permission naming & route middleware
Permission names follow {module}.{level} with no spaces (only dots), e.g. users.index/users.create/users.update/users.detail/users.delete. Protect CRUD routes with the custom `permission:{name}` middleware alias (CheckPermission, uses $user->can so Super Admin bypasses). Guard the Livewire delete() actions too via abort_unless(...,403), and hide create/update/delete buttons with @can. Seed via RolesAndPermissionsSeeder (permission/roles/users modules + Super Admin role holding all permissions).
