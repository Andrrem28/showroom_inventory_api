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
        // ─────────────────────────────────────
        // Permissões
        // ─────────────────────────────────────
        $permissions = [
            // Produtos
            ['name' => 'products.manage',          'description' => 'Gerenciar produtos'],
            ['name' => 'products.view',            'description' => 'Visualizar produtos'],
            ['name' => 'products.create',          'description' => 'Criar produtos'],
            ['name' => 'products.edit',            'description' => 'Editar produtos'],
            ['name' => 'products.delete',          'description' => 'Excluir produtos'],

            // Estoque
            ['name' => 'stock.view',               'description' => 'Visualizar movimentações de estoque'],
            ['name' => 'stock.create',             'description' => 'Registrar entrada ou saída de estoque'],

            // Vendas
            ['name' => 'sales.view',               'description' => 'Visualizar vendas'],
            ['name' => 'sales.create',             'description' => 'Registrar vendas'],
            ['name' => 'sales.delete',             'description' => 'Cancelar ou excluir vendas'],

            // Cadastros auxiliares
            ['name' => 'categories.manage',        'description' => 'Gerenciar categorias e marcas'],
            ['name' => 'suppliers.manage',         'description' => 'Gerenciar fornecedores'],
            ['name' => 'storage-locations.manage', 'description' => 'Gerenciar locais de armazenamento'],
            ['name' => 'clients.manage',           'description' => 'Gerenciar clientes'],

            // Administração
            ['name' => 'users.manage',             'description' => 'Gerenciar usuários e papéis'],
            ['name' => 'reports.view',             'description' => 'Acessar relatórios e dashboard'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // ─────────────────────────────────────
        // Role Admin
        // ─────────────────────────────────────
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Acesso total ao sistema']
        );

        // Associa todas as permissions à role admin
        $adminRole->permissions()->sync(Permission::all());

        // ─────────────────────────────────────
        // Usuário Administrador
        // ─────────────────────────────────────
        $user = User::firstOrCreate(
            ['email' => 'admin@salao.com'],
            [
                'name'      => 'Administrador',
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $user->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
