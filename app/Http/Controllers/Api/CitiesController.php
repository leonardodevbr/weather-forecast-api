<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class CitiesController extends Controller
{
    /**
     * Returns a simple list of cities
     *
     * @param null $lon
     * @param null $lat
     * @return JsonResponse
     */
    public function listing($lon = null, $lat = null): JsonResponse
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

    /**
     * Returns a list of cities that have available weather information.
     *
     * @return JsonResponse
     */
    public function listingWeatherAvailable(): JsonResponse
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

    /**
     * Returns information about a city and its climate
     *
     * @param $cityName
     * @param null $from
     * @param null $to
     * @return JsonResponse
     */
    public function show($cityName, $from = null, $to = null): JsonResponse
    {
        $response = $this->createResponse();
        try {

            $query = Controller::getCitiesCollection();
            if (!empty($from) && !empty($to)) {
                $query = Controller::getCitiesCollection(true, $from, $to);
            }

            $city = $query->where('name', $cityName)->first();

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


    /**
     * Returns an array of cities that can be filtered by those with or without climate information.
     *
     * @param bool $onlyWithWeatherAvailable
     * @return array
     */
    public function getCities($onlyWithWeatherAvailable = false): array
    {
        $cities = Controller::getCitiesCollection($onlyWithWeatherAvailable)
            ->all();

        return $cities;
    }

    /**
     * Returns an array of cities filtered by geolocation.
     *
     * @param $lon
     * @param $lat
     * @return array
     */
    public function getCitiesByGeolocation($lon, $lat): array
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
