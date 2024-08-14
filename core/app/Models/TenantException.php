<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantException extends Model
{
    use HasFactory;
    protected $fillable = ['tenant_id','issue_type','description','domain_create_status','seen_status'];

    protected $casts = [
        'tenant_id' => 'string',
        'domain_create_status' => 'integer'
    ];


    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class,'tenant_id','id');
    }
}
