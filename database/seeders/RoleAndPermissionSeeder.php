use Spatie\Permission\Models\Permission;

Permission::create(['name' => 'view_any_leave::request']);
Permission::create(['name' => 'view_leave::request']);
Permission::create(['name' => 'create_leave::request']);
Permission::create(['name' => 'update_leave::request']);
Permission::create(['name' => 'delete_leave::request']);
Permission::create(['name' => 'approve_leave::request']);
Permission::create(['name' => 'reject_leave::request']); 