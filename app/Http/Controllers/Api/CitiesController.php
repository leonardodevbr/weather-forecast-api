<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CitiesController extends Controller
{
    public function listing($lon = null, $lat = null)
    {
        $response = $this->createResponse();

        try {

            if (!empty($lon) && !empty($lat)) {
                $cities = $this->getCitiesByGeolocation($lon, $lat);
            } else {
                $cities = $this->getCities();
            }

            $response->data = [
                'cities' => $cities
            ];
            $response->success = true;
            $response->title = 'Success:';
            $response->message = '';
            $response->type = 'success';
        } catch (\Exception $ex) {
            $response->title = 'Error:';
            $response->message = $ex->getMessage();
            $response->type = 'error';
        }

        return Response::json($response);
    }

    public function listingWeatherAvailable()
    {
        $response = $this->createResponse();
        try {

            $cities = $this->getCities(true);

            $response->data = [
                'cities' => $cities
            ];

            $response->success = true;
            $response->title = 'Success:';
            $response->message = '';
            $response->type = 'success';
        } catch (\Exception $ex) {
            $response->title = 'Error:';
            $response->message = $ex->getMessage();
            $response->type = 'error';
        }
        return Response::json($response);
    }

    public function show($cityName, $from = null, $to = null)
    {
        $response = $this->createResponse();
        try {

            $city = Controller::getCitiesCollection(true, $from, $to)
                ->where('name', $cityName)->first();

            $response->data = [
                'city' => $city
            ];
            $response->success = true;
            $response->title = 'Success:';
            $response->message = '';
            $response->type = 'success';
        } catch (\Exception $ex) {
            $response->title = 'Error:';
            $response->message = $ex->getMessage();
            $response->type = 'error';
        }
        return Response::json($response);
    }

    public function getCities($withWeatherAvailable = false)
    {
        $cities = Controller::getCitiesCollection($withWeatherAvailable)
            ->all();

        return $cities;
    }

    public function getCitiesByGeolocation($lon, $lat)
    {
        $cities = Controller::getCitiesCollection(true)
            ->all();

        $cities = array_filter($cities, function ($city) use ($lon, $lat) {
            if ($city['coord']['lon'] == $lon && $city['coord']['lat'] == $lat) {
                return $city;
            }
        });

        return $cities;
    }
}
