<?php

namespace App\Http\Controllers;

use App\Model\City;
use App\Model\Weather;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

    /**
     * Retrieves a city list from a .json file and converts it to a collection.
     * This collection can be filtered by those with weather information and a
     * range of weather dates.
     *
     * @param bool $onlyWithWeatherAvailable
     * @param null $from
     * @param null $to
     * @return Collection
     */
    public static function getCitiesCollection($onlyWithWeatherAvailable = false, $from = null, $to = null): Collection
    {
        $arrayCities = json_decode(file_get_contents(database_path('city_list.json')), true);
        $citiesList = [];
        foreach ($arrayCities as $cityData) {
            $city = new City();
            $city->fill($cityData);
            $weathers = Controller::getWeatherCollection($from, $to)->where('city_id', $city->id)->all();
            $city['weathers'] = $weathers;

            if ($onlyWithWeatherAvailable) {
                if (!empty($weathers)) {
                    array_push($citiesList, $city->fill($cityData));
                }
            } else {
                array_push($citiesList, $city->fill($cityData));
            }
        }

        return Collection::make($citiesList);
    }

    /**
     * Retrieves a weather list from a .json file and converts it to a collection.
     * This collection can be filtered by the range of weather dates.
     *
     * @param null $from
     * @param null $to
     * @return Collection
     */
    public static function getWeatherCollection($from = null, $to = null): Collection
    {
        $arrayWeathers = json_decode(file_get_contents(database_path('weather_list.json')), true);
        $weatherList = [];

        foreach ($arrayWeathers as $city) {
            foreach ($city['data'] as $weathers) {
                $weather['city_id'] = $city['cityId'];
                foreach ($weathers as $key => $value) {
                    if ($key == 'dt') {
                        $weather['dt_formatted'] = Carbon::createFromTimestamp($value, 'America/Sao_Paulo')->format('d/m/Y');
                    }
                    $weather[$key] = $value;
                }

                if (empty($from) && empty($to)) {
                    array_push($weatherList, $weather);
                } else {
                    if ($weather['dt'] >= strtotime($from) && $weather['dt'] < strtotime('+1 day', strtotime($to))) {
                        array_push($weatherList, $weather);
                    }
                }
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
