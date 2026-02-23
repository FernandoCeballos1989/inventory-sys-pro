<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class DemoSandbox
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Obtener ID de sesión (asegúrate de que la sesión esté iniciada)
        $sessionId = session()->getId();

        $masterPath = database_path('demos/master.sqlite');
        $userDbPath = database_path("demos/demo_{$sessionId}.sqlite");

        // 2. Si no existe la copia para este usuario, la creamos desde el maestro
        if (!File::exists($userDbPath) && File::exists($masterPath)) {
            File::copy($masterPath, $userDbPath);
        }

        // 3. Conectar dinámicamente a la copia del usuario
        if (File::exists($userDbPath)) {
            Config::set('database.connections.sqlite.database', $userDbPath);
            DB::purge('sqlite');
            DB::reconnect('sqlite');
        }
        return $next($request);
    }
}
