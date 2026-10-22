<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Safe database reads for admin list pages on live (missing tables/columns → message, not 500).
 */
class AdminDbLoader
{
    public const PATCH_HINT = 'Import live-db/professionalonli_live_patch.sql in phpMyAdmin, then run: php artisan config:clear && cache:clear && view:clear';

    public static function hasTable(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function emptyPaginator(int $perPage = 25): LengthAwarePaginator
    {
        return new LengthAwarePaginator([], 0, $perPage, max(1, (int) request()->input('page', 1)), [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }

    /**
     * @return array{items: Collection, error: string|null}
     */
    public static function tableRows(string $table, string $orderBy = 'id', string $direction = 'asc'): array
    {
        try {
            if (! self::hasTable($table)) {
                return [
                    'items' => collect(),
                    'error' => 'Table `'.$table.'` is missing. '.self::PATCH_HINT,
                ];
            }

            return [
                'items' => collect(DB::table($table)->orderBy($orderBy, $direction)->get()),
                'error' => null,
            ];
        } catch (\Throwable $e) {
            \Log::error('AdminDbLoader::tableRows failed', ['table' => $table, 'message' => $e->getMessage()]);

            return [
                'items' => collect(),
                'error' => 'Could not load `'.$table.'`. '.$e->getMessage(),
            ];
        }
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $modelClass
     * @param  callable(Builder):void|null  $query
     * @return array{items: \Illuminate\Contracts\Pagination\LengthAwarePaginator|Collection, error: string|null}
     */
    public static function modelList(string $modelClass, int $perPage = 25, callable $query = null)
    {
        try {
            $model = new $modelClass();
            $table = $model->getTable();

            if (! self::hasTable($table)) {
                return [
                    'items' => self::emptyPaginator($perPage),
                    'error' => 'Table `'.$table.'` is missing. '.self::PATCH_HINT,
                ];
            }

            $builder = $modelClass::query();
            if ($query) {
                $query($builder);
            }

            if ($perPage > 0) {
                return ['items' => $builder->paginate($perPage), 'error' => null];
            }

            return ['items' => $builder->get(), 'error' => null];
        } catch (\Throwable $e) {
            \Log::error('AdminDbLoader::modelList failed', ['model' => $modelClass, 'message' => $e->getMessage()]);

            return [
                'items' => $perPage > 0 ? self::emptyPaginator($perPage) : collect(),
                'error' => 'Could not load data. '.$e->getMessage(),
            ];
        }
    }

    /**
     * @return array{count: int, error: string|null}
     */
    public static function safeCount(string $table): array
    {
        try {
            if (! self::hasTable($table)) {
                return ['count' => 0, 'error' => 'Table `'.$table.'` is missing.'];
            }

            return ['count' => (int) DB::table($table)->count(), 'error' => null];
        } catch (\Throwable $e) {
            return ['count' => 0, 'error' => $e->getMessage()];
        }
    }
}
