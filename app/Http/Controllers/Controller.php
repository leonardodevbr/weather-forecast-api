<?php

namespace App\Http\Controllers;

use App\Model\City;
use App\Model\Weather;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use function foo\func;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Creates an object used to return a response in JSON format. The object
     * contains the following properties: success, message, type and result.
     * @return object
     */
    protected function createResponse()
    {
        return (object)array(
            'success' => false,
            'message' => null,
            'type' => '',
            'result' => null
        );
    }

    public static function getCitiesCollection(): Collection
    {
        $arrayCities = json_decode(file_get_contents(database_path('city_list.json')), true);
        $citiesList = [];
        foreach ($arrayCities as $cityData) {
            $city = new City();
            array_push($citiesList, $city->fill($cityData));
            $city['weathers'] = Controller::getWeatherCollection()->where('city_id', $city->id)->all();
        }

        return Collection::make($citiesList);
    }

    public static function getWeatherCollection(): Collection
    {
        $arrayWeathers = json_decode(file_get_contents(database_path('weather_list.json')), true);
        $weatherList = [];

        foreach ($arrayWeathers as $city) {
            foreach ($city['data'] as $weathers) {
                foreach ($weathers as $key => $weather) {
                    $temp['city_id'] = $city['cityId'];
                    $temp[$key] = $weather;
                }
                array_push($weatherList, $temp);
            }
        }

        $weatherModelList = [];

        foreach ($weatherList as $weatherData) {
            $weather = new Weather();
            array_push($weatherModelList, $weather->fill($weatherData));
        }

        return Collection::make($weatherModelList);
    }
}
