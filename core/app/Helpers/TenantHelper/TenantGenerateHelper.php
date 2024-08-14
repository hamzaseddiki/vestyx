<?php

namespace App\Helpers\TenantHelper;

use Illuminate\Support\Facades\Artisan;
use Stancl\Tenancy\Contracts\ManagesDatabaseUsers;

class TenantGenerateHelper
{

    private $tenant;
    private static $insatnace;

    public static function init($tenant){
        if (is_null(self::$insatnace)){
            $ins = new static();
            $ins->setTenant($tenant);
            return $ins;
        }
        return self::$instance;
    }
    private function setTenant($tenant){
        $this->tenant = $tenant;
        return $this;
    }
    public function getTenant(){
        return $this->tenant;
    }
    private function getDatabaseInstance(){
       return $this->getTenant()->database();
    }
    private function getDatabaseManager(){
        return $this->getDatabaseInstance()->manager();
    }

    public function createDatabase(){
        abort_if($this->databaseExists(),501,__("database already created for tenant")." ".$this->getTenant()->getTenantKey());
        try {
            $this->getDatabaseManager()->createDatabase($this->getTenant());
        }catch (\Exception $e){
            abort(501,$e->getMessage());
        }
    }
    public function databaseExists(){
        try {
            return $this->getDatabaseManager()->databaseExists($this->getDatabaseInstance()->getName());
        }catch (\Exception $e){

        }

        return false;
    }
    public function domainExists(){
        if (is_null($this->getTenant()->domains())){
            return false;
        }
       return !is_null($this->getTenant()->domains()->first());
    }
    public function getDomain(){
        if (!$this->domainExists()){
            return null;
        }
        return $this->getTenant()->domains()->first();
    }
    public function userExists(){
        if (is_null($this->getTenant()->user_id)){
            return false;
        }
        return !is_null($this->getTenant()->user()->first());
    }
    public function getUser(){
        if (!$this->userExists()){
            return null;
        }
        return $this->getTenant()->user()->first();
    }
    public function runMigration(){
        if (!$this->databaseExists()){
            return;
        }

        abort_if(!$this->hasPermissoinToCreateDatabase(),501,__("did not have permission to create new database"));

        try {
            Artisan::call('tenants:migrate', [
                '--tenants' => [$this->getTenant()->getTenantKey()],
            ]);
        }catch (\Exception $e){
            abort(501,$e->getMessage());
        }
    }
    public function runSeeder(){
        if (!$this->databaseExists()){
            return;
        }

        abort_if(!$this->hasPermissoinToCreateDatabase(),501,__("did not have permission to create new database"));

        try {
            Artisan::call('tenants:seed', ['--force' => true,"--tenants" => $this->getTenant()->getTenantKey()]);
        }catch (\Exception $e){
            abort(501,$e->getMessage());
        }


    }
    public function hasPermissoinToCreateDatabase(){
        $manager = $this->getDatabaseManager();
        if ($manager instanceof ManagesDatabaseUsers && $manager->userExists($this->getDatabaseInstance()->getUsername())) {
            return false;
        }
        return true;
    }

    public function createDomain(){
        $this->getTenant()->domains()->create(['domain' => $this->getTenant()->getTenantKey().'.'.env('CENTRAL_DOMAIN')]);
        return $this;
    }


    public function deleteTenant(){
        $this->getTenant()->delete();
        $this->setTenant(null);
    }

}
