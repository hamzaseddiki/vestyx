<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class WebsiteInstruction extends Model implements SyncMaster
{
    use HasTranslations, ResourceSyncing, CentralConnection;
    public $with = ['imageDetails'];
    protected $table = 'website_instructions';
    protected $fillable = ['title','description','repeater_data','status','image','unique_key'];
    protected $translatable = ['title','description'];

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function getTenantModelName(): string
    {
        return 'WebsiteInstruction';
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'unique_key';
        // TODO: Implement getGlobalIdentifierKeyName() method.
    }

    public function getGlobalIdentifierKey()
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function getCentralModelName(): string
    {
        return static::class;
        // TODO: Implement getCentralModelName() method.
    }

    public function getSyncedAttributeNames(): array
    {
        return ['unique_key'];
        // TODO: Implement getSyncedAttributeNames() method.
    }

    public function triggerSyncEvent()
    {
        return null;
        // TODO: Implement triggerSyncEvent() method.
    }
    public function imageDetails(){
      return  $this->belongsTo(MediaUploader::class,'image');
    }

}
