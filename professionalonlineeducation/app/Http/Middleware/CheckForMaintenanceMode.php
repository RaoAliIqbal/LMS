<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CheckForMaintenanceMode extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->isEnvMaintenanceEnabled() && ! $this->hasBypass($request)) {
            if ($this->isHomepageOnlyRequest($request)) {
                throw new HttpException(503, 'Maintenance');
            }
        }

        return parent::handle($request, $next);
    }

    /**
     * When MAINTENANCE_MODE=true, block only the public homepage — not checkout, login, or user area.
     */
    protected function isHomepageOnlyRequest($request): bool
    {
        return $request->is('/') || $request->is('home');
    }

    protected function isEnvMaintenanceEnabled(): bool
    {
        return filter_var(env('MAINTENANCE_MODE', false), FILTER_VALIDATE_BOOLEAN);
    }

    protected function hasBypass($request): bool
    {
        $secret = env('MAINTENANCE_BYPASS_SECRET');

        if (empty($secret)) {
            return false;
        }

        if ($request->query('maintenance_bypass') === $secret) {
            cookie()->queue('maintenance_bypass', $secret, 60 * 24);

            return true;
        }

        return $request->cookie('maintenance_bypass') === $secret;
    }
}
