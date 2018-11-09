<?php

namespace App\Http\Middleware;

use App\Store\LocalizeCurrency as LocalizeCurrencyStore;
use Closure;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class LocalizeCurrency
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $store = new LocalizeCurrencyStore;

        // Fetch the currency code of the visitor to generate the
        // local currency amount equivalent to the base currency amount
        // for the them.
        // $url = 'http://www.geoplugin.net/php.gp?ip=182.69.8.204';
        $url = 'http://www.geoplugin.net/php.gp?ip=' . $request->ip();

        $data = unserialize(file_get_contents($url));
        $currency = $data['geoplugin_currencyCode'];

        if (! $store->hasCurrency($currency)) {
            $store->setCurrency($currency);
        }

        return $next($request);
    }
}
