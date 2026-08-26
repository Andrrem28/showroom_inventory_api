<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Cria as permissions
        $permissions = [
            ['name' => 'products.view',   'description' => 'Visualizar produtos'],
            ['name' => 'products.create', 'description' => 'Criar produtos'],
            ['name' => 'products.edit',   'description' => 'Editar produtos'],
            ['name' => 'products.delete', 'description' => 'Excluir produtos'],
            ['name' => 'stock.view',      'description' => 'Visualizar movimentações de estoque'],
            ['name' => 'stock.create',    'description' => 'Registrar entrada ou saída de estoque'],
            ['name' => 'sales.view',      'description' => 'Visualizar vendas'],
            ['name' => 'sales.create',    'description' => 'Registrar vendas'],
            ['name' => 'sales.delete',    'description' => 'Cancelar ou excluir vendas'],
            ['name' => 'suppliers.manage',  'description' => 'Gerenciar fornecedores'],
            ['name' => 'categories.manage', 'description' => 'Gerenciar categorias e marcas'],
            ['name' => 'clients.manage',    'description' => 'Gerenciar clientes'],
            ['name' => 'users.manage',    'description' => 'Gerenciar usuários e papéis'],
            ['name' => 'reports.view',    'description' => 'Acessar relatórios e dashboard'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // Cria a role admin
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Acesso total ao sistema']
        );

        // Associa todas as permissions à role admin
        $adminRole->permissions()->sync(Permission::all());

        // Cria o usuário administrador
        $user = User::firstOrCreate(
            ['email' => 'admin@salao.com'],
            [
                'name'      => 'Administrador',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // Associa a role admin ao usuário
        $user->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
