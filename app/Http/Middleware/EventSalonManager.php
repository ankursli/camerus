<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EventSalonManager
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws Exception
     */
    public function handle($request, Closure $next)
    {
        /**
         * Set salon to session
         */
//        $salon_request = $request->request->get(SLUG_EVENT_SALON_QUERY);
//        $current_salon = getEventSalonSlugInSession();
//
//        if (!empty($current_salon)) {
//            setEventSalonCitySlugToSession(EVENT_TYPE_DEFAULT_SLUG);
//        }

//        if (!empty($current_salon)) {
//            if (!empty($salon_request) && $current_salon !== $salon_request) {
//                addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $salon_request);
//                wc()->cart->empty_cart();
//            } elseif (!empty($salon_request)) {
//                addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $salon_request);
//            }
//            $current_salon_id = getPostIdBySlug($current_salon);
//            if (!empty($current_salon_id) && isOverSalonLimitDate($current_salon_id)) {
//                removeEventSalonSlugInSession();
//                wc()->cart->empty_cart();
//            }
//        } else {
////            $current_city = getEventSalonCitySlugInSession();
//            $request_city = getCityBySalonSlug($salon_request);
//            if (!empty($salon_request) && !empty($request_city)) {
//                addEventSalonSlugToSession(SLUG_EVENT_SALON_QUERY, $salon_request);
//                wc()->cart->empty_cart();
//            }
//        }

        /**
         * Filter product by GEO-position : Paris or Région
         */
//        $reset_salon_slug = $request->request->get('reset_salon_slug');
//        if (!empty($reset_salon_slug) && $reset_salon_slug === "1") {
//            removeEventSalonSlugInSession();
//            wc()->cart->empty_cart();
//        }

        /**
         * Set salon city to session
         */
//        $city = $request->request->get(SLUG_EVENT_CITY_QUERY);
//        if (!empty($city)) {
//            addEventSalonCitySlugToSession(SLUG_EVENT_CITY_QUERY, $city);
//        }

//        if (empty($salon_request) && (is_shop() || is_product_category() || is_product_tag())) {
////            wc()->cart->empty_cart();
////            removeEventSalonSlugInSession(SLUG_EVENT_SALON_QUERY);
//        }
//
//        if (empty($city) && (is_shop() || is_product_category() || is_product_tag())) {
////            removeEventSalonSlugInSession(SLUG_CITY_SESSION_SALON);
//        }

        return $next($request);
    }
}
