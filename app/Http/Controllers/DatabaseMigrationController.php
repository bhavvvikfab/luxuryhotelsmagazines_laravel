<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artisan;

class DatabaseMigrationController extends Controller
{
    public function migrate()
    {
        try {
            Artisan::call('migrate');
            return 'Migration completed successfully.';
        } catch (\Exception $e) {
            return 'Migration failed: ' . $e->getMessage();
        }
    }

    public function swaggerGenerate(){
        try{
            Artisan::call('l5-swagger:generate');
            return 'Swagger Generate successfully.';
        } catch (\Exception $e) {
            return 'Swagger Generate failed: ' . $e->getMessage();
        }
    }
}
