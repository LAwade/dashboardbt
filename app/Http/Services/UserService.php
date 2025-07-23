<?php 

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class UserService {

    public function findAll(){
        try {
            $users = User::with(['permission'])->orderBy('name')->paginate(10);
            return $users;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro buscar usuário no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } 
        return collect();
    }

    public function findByName($name){
        try {
            $user = User::with(['permission'])->whereRaw('name ILIKE ?', ["%{$name}%"])->orderBy('name')->paginate(10);
            return $user;
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro buscar usuário no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        } 
        return collect();
    }

    public function create(array $data)
    {
        try {
            $data['id'] = Str::uuid();
            $user = User::create($data);
            return $user;
        } catch (\Exception $e) {
            Log::error('Erro ao criar a equipe no banco de dados', [
                'exception' => $e->getMessage(),
                'data' => $data
            ]);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao criar a usuário no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function update(string $id, array $data)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return collect();
            }
            return $user->update($data);
        } catch (QueryException $e) {
            Log::channel('database_errors')->error(__FUNCTION__ . ' - Erro ao atualizar a usuário no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }

    public function delete(string $id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                return collect();
            }
            return $user->delete();
        } catch (QueryException $e) {
            Log::error(__FUNCTION__ . ' - Erro ao excluir usuário no banco de dados', [
                'exception' => $e->getMessage(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
        }
        return collect();
    }
}