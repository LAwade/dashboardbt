<?php

namespace App\Http\Services;

use App\Models\SetResult;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SetResultService
{

    public function findByGame($id)
    {
        try {
            return SetResult::where('game_id', $id)->get();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao buscar a resultado no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return false;
    }

    public function create(array $data)
    {
        try {
            $data['id'] = (string)Str::uuid();
            $set = SetResult::create($data);
            return $set;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao criar a resultado no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return false;
    }

    public function updateOrCreate(array $where, array $data)
    {
        try {
            $set = SetResult::updateOrCreate($where, $data);
            return $set->update($data);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao atualizar/criar a resultado no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return false;
    }

    public function delete(string $id)
    {
        try {
            $set = SetResult::find($id);
            return $set->delete();
        } catch (QueryException $e) {
            Log::channel('database_errors')->error('Erro ao remover a resultado no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return false;
    }
}
