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
            $cities = $this->getCities();

            if (!empty($lon) && !empty($lat)) {
                $cities = $this->getCitiesByGeolocation($lon, $lat);
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

            $city = Controller::getCitiesCollection()
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
        $cities = Controller::getCitiesCollection()
            ->all();

        if ($withWeatherAvailable) {
            $cities = array_filter($cities, function ($city) {
                if (!empty($city->weathers)) {
                    return $city;
                }
            });
        }

        return $cities;
    }

    public function getCitiesByGeolocation($lon, $lat)
    {
        $cities = Controller::getCitiesCollection()
            ->all();

        return $cities;
    }
}
