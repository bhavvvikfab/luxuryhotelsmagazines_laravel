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
}
