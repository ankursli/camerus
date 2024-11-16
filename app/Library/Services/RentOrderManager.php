<?php
/**
 * Rent+ Order Management
 */

namespace App\Library\Services;

use App\Hooks\Salon;
use App\Library\Services\Contracts\SendRentServiceInterface;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use WC_Order;

class RentOrderManager implements SendRentServiceInterface
{
    protected $base_url, $order;

    public function __construct()
    {
        $this->base_url = URL_SERVICE_RENT_PLUS;
    }

    /**
     * @param $xml
     *
     * @throws GuzzleException
     */
    public function sendRequest($xml)
    {
        $xml = 'param='.str_replace("\n", '', (string)$xml);
        $client = new Client();
        $res = $client->request('POST', $this->base_url, [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                //                'SOAPAction'   => 'www.rentplus.be/webservices/WriteOrder'
            ],
            'body' => $xml,
        ]);

        try {
            echo $res->getStatusCode();
            echo $res->getReasonPhrase();
            if (! empty($body = $res->getBody())) {
                echo $body->getContents();
            }
        } catch (RequestException $e) {
            echo $e->getRequest()."\n";
            if ($e->hasResponse()) {
                echo $e->getResponse()."\n";
            }
        }
    }

    public function getXmlData($order)
    {
        if (! $order instanceof WC_Order) {
            return null;
        } elseif (is_int($order)) {
            $order = wc_get_order($order);
        }

        $data = [];
        $data['salon'] = $salon = null;
        $data['order_id'] = $order->get_id();
        $data['module_number'] = '002';
        $data['order'] = $order;
        $data['items'] = $order->get_items();
        $data['meta'] = $order->get_meta_data();
        $data['salon_cp'] = '';
        $data['salon_city'] = '';
        $data['salon_country'] = 'FRANCE';
        $data['salon_ville_name'] = '';
        $data['salon_period_in'] = '';
        $data['salon_period_out'] = '';
        $data['availibility_period_out'] = date('Y-m-d', time()).'T'.date("H:i:s", time());
        $data['availibility_period_in'] = date('Y-m-d', time()).'T'.date("H:i:s", time());
        $data['salon_location_key'] = '0000';
        $data['salon_rate'] = '';
        $data['address_line_1'] = $order->get_billing_address_1();
        $data['postal_code'] = $order->get_billing_postcode();
        $data['hall'] = $order->get_meta('hall_stand');
        $data['passage'] = $order->get_meta('allee_stand');
        $data['stand'] = $order->get_meta('numero_de_stand');
        $data['module_name'] = $order->get_meta('nom_stand');

        $salon_slug = $order->get_meta('slug_evenement');
        $event_type = $order->get_meta('event_type');
        if (empty($event_type)) {
            $event_type = getEventSalonCitySlugInSession();
        }
        if (! empty($salon_slug)) {
            $_salon = Salon::getSalon([
                'name' => $salon_slug,
                'post_status' => 'publish',
                'posts_per_page' => 1
            ]);
            if (! empty($_salon) && is_array($_salon)) {
                $salon = reset($_salon);

                $availibility_period_out = get_field('date_availibility_out', $salon->ID);
                if (empty($availibility_period_out)) {
                    $salon_start_date = $salon->salon_start_date;
                    $salon_start_date = Carbon::createFromFormat('Y-m-d', $salon_start_date);
                    $salon_start_date = $salon_start_date->subDays(7);
                    $availibility_period_out = $salon_start_date->format('Y-m-d');
                }
                $availibility_period_in = get_field('date_availibility_in', $salon->ID);
                if (empty($availibility_period_in)) {
                    $salon_end_date = $salon->salon_end_date;
                    $salon_end_date = Carbon::createFromFormat('Y-m-d', $salon_end_date);
                    $salon_end_date = $salon_end_date->addDays(7);
                    $availibility_period_in = $salon_end_date->format('Y-m-d');
                }

                $data['salon_cp'] = $salon->salon_cp ? $salon->salon_cp : '';
                $data['salon_ville_name'] = $salon->salon_ville_name ? $salon->salon_ville_name : '';
                $data['salon_period_in'] = date('Y-m-d', strtotime($salon->salon_end_date)).'T'.date("H:i:s", strtotime($salon->salon_end_date));
                $data['salon_period_out'] = date('Y-m-d', strtotime($salon->salon_start_date)).'T'.date("H:i:s", strtotime($salon->salon_start_date));
                $data['availibility_period_in'] = date('Y-m-d', strtotime($availibility_period_in)).'T'.date("H:i:s", strtotime($availibility_period_in));
                $data['availibility_period_out'] = date('Y-m-d', strtotime($availibility_period_out)).'T'.date("H:i:s",
                        strtotime($availibility_period_out));
                $data['salon_location_key'] = get_field('salon_location_key', $salon->ID);
                $data['salon_rate'] = getSalonRateById($salon->ID);
            }
        } elseif (! empty($event_type)) {
            $data['salon_ville_name'] = $order->get_meta('lieu_evenement');
            $data['salon_city'] = $order->get_meta('ville_evenement');
            $salon_start_date = Carbon::createFromFormat('d-m-Y', $order->get_meta('date_evenement'));
            $salon_end_date = Carbon::createFromFormat('d-m-Y', $order->get_meta('date_fin_evenement'));

            $availibility_period_out = $salon_start_date->subDays(7);
            $availibility_period_out = $availibility_period_out->format('Y-m-d');

            $availibility_period_in = $salon_start_date->addDays(7);
            $availibility_period_in = $availibility_period_in->format('Y-m-d');

            $data['salon_period_in'] = $salon_end_date->format('Y-m-d').'T'.date("H:i:s", strtotime($salon_end_date->format('Y-m-d')));
            $data['salon_period_out'] = $salon_start_date->format('Y-m-d').'T'.date("H:i:s", strtotime($salon_start_date->format('Y-m-d')));
            $data['availibility_period_in'] = $availibility_period_in.'T'.date("H:i:s", strtotime($availibility_period_in));
            $data['availibility_period_out'] = $availibility_period_out.'T'.date("H:i:s", strtotime($availibility_period_out));

            $term_city = get_term_by('slug', $event_type, 'pa_city');
            if (! empty($term_city)) {
                $term_id = $term_city->term_id;
                $rent_price_code = get_field('app_rent_plus_price_code', 'pa_city_'.$term_id);
                if (! empty($rent_price_code)) {
                    $data['salon_rate'] = $rent_price_code;
                }
            }
            if (empty($data['salon_rate'])) {
                switch ($event_type) {
                    case 'paris' :
                        $salon_rate = 'S';
                        break;
                    case 'region' :
                        $salon_rate = 'T';
                        break;
                    case 'event' :
                        $salon_rate = 'U';
                        break;
                    default:
                        $salon_rate = $event_type;
                        break;
                }
                $data['salon_rate'] = $salon_rate;
            }
        }

        $xml_data = [];
        if (! empty($data) && is_array($data)) {
            foreach ($data as $key => $datum) {
                if (is_string($datum)) {
                    $xml_data[$key] = $this->escapeXmlStringContent($datum);
                } else {
                    $xml_data[$key] = $datum;
                }
            }
        }

        return $xml_data;
    }

    public function sendToRent($order_id, $new_content = '')
    {
        if (! empty($new_content)) {
            $new_content = get_field('order_rent_xml_content', $order_id);
        }
        $this->saveXmlFile($order_id, $new_content);
        $this->sendRequest($new_content);
    }

    public function getXmlFileName($order_id)
    {
        return 'rentfiles/rent-xml-'.$order_id.'.xml';
    }

    public function getXmlContent($order_id)
    {
        $contents = '';
        $file_name = $this->getXmlFileName($order_id);
        if (Storage::exists($file_name)) {
            $contents = Storage::get($file_name);
        }

        return $contents;
    }

    public function escapeXmlStringContent($xml)
    {
        $content = str_replace(['"', "'", '>', '<'], '-', $xml);
        $content = str_replace(['&'], 'et', $content);

        return $content;
    }

    public function getXmlTemplateContent($data)
    {
        return View::make('shop.rentplus.xml-item', $data)->render();
    }

    public function saveXmlFile($order_id, $contents)
    {
        $file_name = $this->getXmlFileName($order_id);
        Storage::put($file_name, $contents);
    }

    public function triggerXmlOrder(WC_Order $order)
    {
        if (app()->environment('production')) {
            $order_id = $order->get_id();
            $data = $this->getXmlData($order);
            $content = $this->getXmlTemplateContent($data);
            $this->saveXmlFile($order_id, $content);
            $this->sendRequest($content);
        }
    }
}