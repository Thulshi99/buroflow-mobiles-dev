<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    // public function users()
    // {
    //     return $this->belongsToMany(CentralUser::class, 'tenant_users', 'tenant_id', 'global_user_id', 'id', 'global_id')
    //         ->using(TenantPivot::class);
    // }
}

// class CentralUser extends Model implements SyncMaster
// {
//     // Note that we force the central connection on this model
//     use ResourceSyncing, CentralConnection;

//     protected $guarded = [];
//     public $timestamps = false;
//     public $table = 'users';

//     public function tenants(): BelongsToMany
//     {
//         return $this->belongsToMany(Tenant::class, 'tenant_users', 'global_user_id', 'tenant_id', 'global_id')
//             ->using(TenantPivot::class);
//     }

//     public function getTenantModelName(): string
//     {
//         return User::class;
//     }

//     public function getGlobalIdentifierKey()
//     {
//         return $this->getAttribute($this->getGlobalIdentifierKeyName());
//     }

//     public function getGlobalIdentifierKeyName(): string
//     {
//         return 'global_id';
//     }

//     public function getCentralModelName(): string
//     {
//         return static::class;
//     }

//     public function getSyncedAttributeNames(): array
//     {
//         return [
//             'name',
//             'password',
//             'email',
//         ];
//     }
// }

// class User extends Model implements Syncable
// {
//     use ResourceSyncing;

//     protected $guarded = [];
//     public $timestamps = false;

//     public function getGlobalIdentifierKey()
//     {
//         return $this->getAttribute($this->getGlobalIdentifierKeyName());
//     }

//     public function getGlobalIdentifierKeyName(): string
//     {
//         return 'global_id';
//     }

//     public function getCentralModelName(): string
//     {
//         return CentralUser::class;
//     }

//     public function getSyncedAttributeNames(): array
//     {
//         return [
//             'name',
//             'password',
//             'email',
//         ];
//     }
// }