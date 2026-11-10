<?php

namespace app\controller\api;

use app\base\BaseIndexEncryptController;
use app\libs\BookingHotel;
use think\Request;
use think\response\Json;


/**
 * 机票接口
 * Class Flights
 * @package app\controller
 */
class Flights extends BaseIndexEncryptController
{
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 获取机场目的地
     * @param Request $request
     * @return Json
     */
    public function getFlightsDestination(Request $request)
    {
        $query = $request->param('query', 'new');//查询关键词
        $list = BookingHotel::getFlightsSearchDestination($query);
        return self::returnMsg(1, $list);
    }

    /**
     * 获取航班列表
     * @param Request $request
     * @return Json
     */
    public function getFlights(Request $request)
    {
//        //$dest_id = '', $search_type = 'CITY', $arrival_date = '', $departure_date = '', $room_qty = 1, $price_min = 0, $price_max = 10000, $page_number = 1, $languagecode = 'en', $currency_code = 'USD'
//        $dest_id = $request->param('dest_id', '');
//        $search_type = $request->param('search_type', 'CITY');
//        $arrival_date = $request->param('arrival_date', '');
//        $departure_date = $request->param('departure_date', '');
//        $room_qty = $request->param('room_qty', 1);
//        $price_min = $request->param('price_min', 0);
//        $price_max = $request->param('price_max', 10000);
//        $page_number = $request->param('page_number', 1);
//        $languagecode = $request->param('languagecode', strtolower(Lang::getLangSet()));
//        $currency_code = $request->param('currency', 'USD');
//        $list = BookingHotel::getSearchHotels($dest_id, $search_type, $arrival_date, $departure_date, $room_qty, $price_min, $price_max, $page_number, $languagecode, $currency_code);
//        return self::returnMsg(1, $list);
    }

    /**
     * 获取航班详情
     * @param Request $request
     * @return Json
     */
    public function getFlightsDetail(Request $request)
    {
        //$hotel_id = '',$arrival_date = '', $departure_date = '', $room_qty = 1, $languagecode = 'en', $currency_code = 'USD'
//        $hotel_id = $request->param('hotel_id', '');
//        $arrival_date = $request->param('arrival_date', '');
//        $departure_date = $request->param('departure_date', '');
//        $room_qty = $request->param('room_qty', 1);
//        $languagecode = $request->param('languagecode', strtolower(Lang::getLangSet()));
//        $currency_code = $request->param('currency', 'USD');
//        $detail = BookingHotel::getSearchHotelDetails($hotel_id, $arrival_date, $departure_date, $room_qty, $languagecode, $currency_code);
//        return self::returnMsg(1, $detail);
    }
}
