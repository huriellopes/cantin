<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\State;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SyncLocalidades extends Command
{
    protected $signature = 'localidades:sync';

    protected $description = 'Sincroniza estados e cidades a partir da API oficial do IBGE';

    public function handle(): int
    {
        $base = mb_rtrim((string) config('services.ibge.endpoint', 'https://servicodados.ibge.gov.br/api/v1/localidades'), '/');

        $this->info('Buscando estados no IBGE...');
        $statesResp = Http::timeout(30)->retry(3, 1500)->get("{$base}/estados");

        if ($statesResp->failed()) {
            $this->error('Falha ao buscar estados no IBGE.');

            return self::FAILURE;
        }

        $this->info('Buscando municípios no IBGE...');
        $citiesResp = Http::timeout(60)->retry(3, 1500)->get("{$base}/municipios");

        if ($citiesResp->failed()) {
            $this->error('Falha ao buscar municípios no IBGE.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($statesResp, $citiesResp): void {
            // Limpa as tabelas (sem dados dependentes: addresses/terreiros referenciam por id).
            Schema::disableForeignKeyConstraints();
            DB::table('cities')->delete();
            DB::table('states')->delete();
            Schema::enableForeignKeyConstraints();

            // Estados: mapa sigla -> id local
            $stateIdBySigla = [];

            foreach ($statesResp->json() as $uf) {
                $state = State::query()->create([
                    'name' => $uf['nome'],
                    'abbr' => $uf['sigla'],
                    'ibge_code' => $uf['id'],
                    'slug' => Str::slug($uf['nome']),
                ]);

                $stateIdBySigla[$uf['sigla']] = $state->id;
            }

            // Municípios em lote
            $now = now();
            $rows = [];

            foreach ($citiesResp->json() as $municipio) {
                $sigla = $municipio['microrregiao']['mesorregiao']['UF']['sigla']
                    ?? $municipio['regiao-imediata']['regiao-intermediaria']['UF']['sigla']
                    ?? null;

                if ($sigla === null || !isset($stateIdBySigla[$sigla])) {
                    continue;
                }

                $rows[] = [
                    'name' => $municipio['nome'],
                    'state_id' => $stateIdBySigla[$sigla],
                    'ibge_code' => $municipio['id'],
                    'slug' => Str::slug($municipio['nome']),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach (array_chunk($rows, 500) as $chunk) {
                DB::table('cities')->insert($chunk);
            }

            $this->info(count($stateIdBySigla) . ' estados e ' . count($rows) . ' cidades sincronizados.');
        });

        return self::SUCCESS;
    }
}
