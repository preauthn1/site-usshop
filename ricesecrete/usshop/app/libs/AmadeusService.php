<?php

namespace app\libs;

use Amadeus\Amadeus;
use think\facade\Config;

class AmadeusService
{
    private Amadeus $amadeus;

    public function __construct()
    {
        $this->amadeus = Amadeus::builder(Config::get('amadeus.api_key'), Config::get('amadeus.api_secret'))
            ->build();
    }

    /**
     * Search flights
     *
     * @param  string  $originCode  Origin location code
     * @param  string  $destinationCode  Destination location code
     * @param  string  $departDate  Departure date (YYYY-MM-DD)
     * @param  int  $adults  Number of adults
     * @return array
     */
    public function searchFlights(string $originCode, string $destinationCode, string $departDate, int $adults = 1): array
    {
        try {
            $response = $this->amadeus->getShopping()->getFlightOffers()->get(
                array(
                    "originLocationCode"      => $originCode,
                    "destinationLocationCode" => $destinationCode,
                    "departureDate"           => $departDate,
                    "adults"                  => $adults
                )
            );

            $result = [];
            foreach ($response as $offer) {
                $result[] = $offer->toArray();
            }

            return $result;
        } catch (\Exception $e) {
            // 记录详细错误信息
            $errorMessage = sprintf("Amadeus API Error: %s", $e->getMessage());
            error_log($errorMessage);
            return [];
        }
    }

    /**
     * Search hotels
     *
     * @param $city_code
     * @return array
     */
    public function searchHotels($city_code): array
    {
        try {
            $response = $this->amadeus->getReferenceData()->getLocations()->getHotels()->getByCity()->get(["cityCode"=>$city_code]);
            $result = [];
            foreach ($response as $hotel) {
                $result[] = $hotel->toArray();
            }
            return $result;
        } catch (\Exception $e) {
            // 记录详细错误信息
            $errorMessage = sprintf("Amadeus API Error: %s", $e->getMessage());
            error_log($errorMessage);
            return [];
        }
    }

    public function getHotelOffer($hotel_id, int $adults = 1, $checkInDate = '', $priceRange = '100-200'): array
    {
        try {
            $response = $this->amadeus->getShopping()->getHotelOffers()->get([
                "hotelIds" => $hotel_id,
                "adults" => $adults,
                'currency' => 'USD',
                'priceRange' => $priceRange,
                'checkInDate' => $checkInDate,
            ]);;
            $result = [];
            foreach ($response as $hotel) {
                $result[] = $hotel->toArray();
            }
            return $result;
        } catch (\Exception $e) {
            // 记录详细错误信息
            $errorMessage = sprintf("Amadeus API Error: %s", $e->getMessage());
            error_log($errorMessage);
            return [];
        }
    }
}
