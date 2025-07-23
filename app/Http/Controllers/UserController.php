<?php

namespace App\Http\Controllers;

use App\Http\Services\PermissionService;
use App\Http\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UserController extends Controller
{

    public function __construct(
        protected UserService $userService,
        protected PermissionService $permissionService
    ) {}

    private function renderInertia(array $flash = [], $users = null)
    {
        if (!$users) {
            $users = $this->userService->findAll();
        }

        $permissions = $this->permissionService->findAll();
        return Inertia::render('Users/Index', [
            'users' => $users,
            'permissions' => $permissions,
            'flash' => $flash
        ]);
    }

    public function index(Request $request)
    {
        $users = [];
        $findUser = [];
        $name = $request->input('search');

        if ($name) {
            $findUser = $this->userService->findByName($name);
        }

        if (count($findUser) > 0) {
            $users = $findUser;
        }
        return $this->renderInertia(users: $users);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required|string|min:6',
                'permission_id' => 'required|exists:permissions,id'
            ]);

            if ($data['password'] != $data['password_confirmation']) {
                throw new Exception('As senhas informadas não confere!');
            }

            $validated['password'] = Hash::make($data['password']);
            $response = $this->userService->create($data);
            if (!$response) {
                throw new Exception('Erro ao salvar o usuário. Tente novamente.');
            }
            return $this->renderInertia(flash: ['success' => "Usuário cadastrado com sucesso!"]);
        } catch (Exception $e) {
            return $this->renderInertia(flash: ['error' => $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
                'password' => 'nullable|string|min:6',
                'password_confirmation' => 'nullable|string|min:6',
                'permission_id' => 'required|exists:permissions,id'
            ]);

            if (isset($data['password']) && $data['password'] != $data['password_confirmation']) {
                throw new Exception('As senhas informadas não confere!');
            }

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            $response = false;
            if (isset($id)) {
                $response = $this->userService->update($id, $data);
            }

            if (!$response) {
                throw new Exception('Erro ao atualizar o usuário. Tente novamente!');
            }
            return $this->renderInertia(flash: ['success' => "Usuário atualizado com sucesso!"]);
        } catch (Exception $e) {
            return $this->renderInertia(flash: ['error' => $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $response = $this->userService->delete($id);
        if (!$response) {
            session()->flash('error', 'Erro ao excluir usuario. Tente novamente!');
        } else {
            session()->flash('success', 'Usuario excluído com sucesso!');
        }
        return Inertia::location(route('users.index'));
    }
}
