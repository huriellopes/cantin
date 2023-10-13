<x-app-layout>
    <style>
        .table {
            background: transparent;
            --bs-table-color: #FFFFFF !important;
            --bs-table-bg: transparent !important;
        }

        .table > tbody {
            color: #FFFFFF !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
{{--        <div>--}}
{{--            <a href="" class="btn btn-primary">Criar Usuário</a>--}}
{{--        </div>--}}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="table table-responsive table-striped" id="table-users">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Nível de Acesso</th>
                                <th>Status</th>
                                <th>Criado em</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="{{ asset('assets/js/users/list.users.js') }}"></script>
