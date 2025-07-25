<?php 

namespace App\Http\Services;

use App\Models\Status;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class StatusService {

    public function findAll(){
        try {
            return Status::all();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao adicionar o status no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } 
        return collect();
    }
}