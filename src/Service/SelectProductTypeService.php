<?php
declare(strict_types=1);

namespace App\Service;

class SelectProductTypeService
{
    private const HEAVY_SNOW = "heavy-snow";
    private const LIGHT_SNOW = "light-snow";
    private const MODERATE_SNOW = "moderate-snow";
    private const HEAVY_RAIN = "heavy-rain";
    private const SLEET = "sleet";
    private const LIGHT_RAIN = "light-rain";
    private const MODERATE_RAIN = "moderate-rain";
    private const ISOLATED_CLOUDS = "isolated-clouds";
    private const SCATTERED_CLOUDS = "scattered-clouds";
    private const OVERCAST = "overcast";
    private const CLEAR = "clear";
    private const FOG = "fog";


    public function selectProductType(array $weatherForecast)
    {
        $tomorrowsDate = new \DateTime('tomorrow');
        $dayAfterDate = new \DateTime('tomorrow');
        $dayAfterDate->add(new \DateInterval('P1D'));

        $todaysWeather = [];
        $tomorrowsWeather = [];

        $weatherForecast = (object)$weatherForecast;
        foreach ($weatherForecast as $weatherForestHourly) {
            $weatherForestHourly = (object)$weatherForestHourly;
            $weatherForecastDate = new \DateTime($weatherForestHourly->forecastTimeUtc);

            if ($weatherForecastDate < $tomorrowsDate) {
                $todaysWeatherConditions[] = $weatherForestHourly->conditionCode;
            }
            if ($weatherForecastDate > $tomorrowsDate && $weatherForecastDate < $dayAfterDate) {
                $tomorrowsWeatherConditions[] = $weatherForestHourly->conditionCode;
            }
        }
        $accessoryGroup['today'] = $this->selectAccessoryType($todaysWeatherConditions);
        $accessoryGroup['tomorrow'] = $this->selectAccessoryType($tomorrowsWeatherConditions);

        return $accessoryGroup;
    }

    private function selectAccessoryType(array $weatherOfTheDay)
    {
        if (in_array(self::HEAVY_SNOW, $weatherOfTheDay)) {
            return "heavy-snow";
        }
        if (in_array(self::LIGHT_SNOW, $weatherOfTheDay) || in_array(self::MODERATE_SNOW, $weatherOfTheDay)) {
            return "snow";
        }
        if (in_array(self::HEAVY_RAIN, $weatherOfTheDay) || in_array(self::SLEET, $weatherOfTheDay)) {
            return "heavy-rain";
        }
        if (in_array(self::LIGHT_RAIN, $weatherOfTheDay) || in_array(self::MODERATE_RAIN, $weatherOfTheDay)) {
            return "rain";
        }
        if (in_array(self::ISOLATED_CLOUDS, $weatherOfTheDay) || in_array(self::SCATTERED_CLOUDS, $weatherOfTheDay) || in_array(self::OVERCAST, $weatherOfTheDay)) {
            return "clouds";
        }
        if (in_array(self::CLEAR, $weatherOfTheDay)) {
            return "clear";
        }
        if (in_array(self::FOG, $weatherOfTheDay)) {
            return "fog";
        }

    }
}