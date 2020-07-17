<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Session;
use App\Boleto;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      Route::bind('prepago', function($value) {
        return Boleto::buscarPrepago($value);
      });

      view()->composer('*', function($view) {
        $taquilla = Session::taquilla();
        $resumen = Session::resumen();
        $moneda = Session::moneda();
        $moneda_alterna = $moneda->alterna();

        $view->with('resumen', $resumen)
          ->with('taquilla', $taquilla)
          ->with('moneda', $moneda)
          ->with('moneda_alterna', $moneda_alterna);
      });
    }
}
