<?php 

namespace App\Http\Services;

use App\Models\Permission;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PermissionService {

    public function findAll(){
        try {
            return Permission::orderBy('name')->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro buscar permissões no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } 
        return collect();
    }
}