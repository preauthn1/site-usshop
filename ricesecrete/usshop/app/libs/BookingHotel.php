<?php


namespace app\libs;


use think\Exception;

class BookingHotel
{
    private static $apiKey = '5678c90b20msh957d515fd3d2325p15200ejsncca27497f8eb';
    private static $apiHost = 'booking-com15.p.rapidapi.com';

    private static $langCode = [
        'en' => 'en-us',
        'zh-cn' => 'zh-cn',
        'es' => 'es',
        'pt' => 'pt-pt',
    ];
//    private static $searchHotels = '/api/v1/hotels/searchHotels';
    //curl --request GET \
    //	--url 'https://booking-com15.p.rapidapi.com/api/v1/hotels/searchHotels?dest_id=-2092174&search_type=CITY&adults=1&children_age=0%2C17&room_qty=1&page_number=1&units=metric&temperature_unit=c&languagecode=en-us&currency_code=AED' \
    //	--header 'x-rapidapi-host: booking-com15.p.rapidapi.com' \
    //	--header 'x-rapidapi-key: 5678c90b20msh957d515fd3d2325p15200ejsncca27497f8eb'

    /**
     * 获取搜索酒店目的地
     * @param string $query 城市名称
     * @return array|string
     */
    public static function getHotelsSearchDestination($query = 'man')
    {
        //api/v1/hotels/searchDestination
        $url = 'https://' . self::$apiHost . '/api/v1/hotels/searchDestination';
        $header = [
            'x-rapidapi-host: ' . self::$apiHost,
            'x-rapidapi-key: ' . self::$apiKey];
        $data = [
            'query' => $query,
        ];
        $result = self::getRequestFile($url, $data, 10, $header);
        if (empty($result)) {
            return [];
        }
        $result = json_decode($result, true);
        if (empty($result) || (!isset($result['data']))) {
            return [];
        }
        return $result['data'];
    }

    /**
     * 获取搜索航班目的地
     * @param string $query 城市名称
     * @return array|mixed
     */
    public static function getFlightsSearchDestination($query = 'new')
    {
        //curl --request GET
        //	--url 'https://booking-com15.p.rapidapi.com/api/v1/flights/searchDestination?query=New%20York'
        //	--header 'x-rapidapi-host: booking-com15.p.rapidapi.com'
        //	--header 'x-rapidapi-key: 5678c90b20msh957d515fd3d2325p15200ejsncca27497f8eb'
        $url = 'https://' . self::$apiHost . '/api/v1/flights/searchDestination';
        $header = [
            'x-rapidapi-host: ' . self::$apiHost,
            'x-rapidapi-key: ' . self::$apiKey];
        $data = [
            'query' => $query,
        ];
        $result = self::getRequestFile($url, $data, 10, $header);
        if (empty($result)) {
            return [];
        }
        $result = json_decode($result, true);
        if (empty($result) || (!isset($result['data']))) {
            return [];
        }
        return $result['data'];
    }

    /**
     * 获取搜索酒店
     * @param string $dest_id
     * @param string $search_type
     * @param string $arrival_date
     * @param string $departure_date
     * @param int $room_qty
     * @param int $price_min
     * @param int $price_max
     * @param int $page_number
     * @param string $languagecode
     * @param string $currency_code
     * @return array|mixed
     */
    public static function getSearchHotels($dest_id = '', $search_type = 'CITY', $arrival_date = '', $departure_date = '', $room_qty = 1, $price_min = 0, $price_max = 10000, $page_number = 1, $languagecode = 'en', $currency_code = 'USD')
    {
        //dest_id=20088325
        //&search_type=CITY
        //&arrival_date=2025-03-10
        //&departure_date=2025-03-11
        //&adults=1
        //&children_age=0%2C17
        //&room_qty=1
        //&page_number=1
        //&units=metric
        //&price_min=10
        //&price_max=300
        //&temperature_unit=c
        //&languagecode=en-us
        //&currency_code=USD
        //curl --request GET \
        //	--url 'https://booking-com15.p.rapidapi.com/api/v1/hotels/searchHotels?dest_id=20088325&search_type=CITY&arrival_date=2025-03-11&departure_date=2025-03-12&adults=1&children_age=0%2C17&room_qty=1&page_number=1&price_min=0&price_max=10000&units=metric&temperature_unit=c&languagecode=en-us&currency_code=USD' \
        //	--header 'x-rapidapi-host: booking-com15.p.rapidapi.com' \
        //	--header 'x-rapidapi-key: 5678c90b20msh957d515fd3d2325p15200ejsncca27497f8eb'
        if (empty($arrival_date) || empty($departure_date)) {
            $arrival_date = date('Y-m-d', strtotime('+1 day'));
            $departure_date = date('Y-m-d', strtotime('+2 day'));
        }
        $url = 'https://' . self::$apiHost . '/api/v1/hotels/searchHotels';
        $header = [
            'x-rapidapi-host: ' . self::$apiHost,
            'x-rapidapi-key: ' . self::$apiKey
        ];
        $data = [
            'dest_id' => $dest_id,
            'search_type' => $search_type,
            'arrival_date' => $arrival_date,
            'departure_date' => $departure_date,
            'adults' => $room_qty,
            'room_qty' => $room_qty,
            'children_age' => '0,17',
            'page_number' => $page_number,
            'price_min' => $price_min,
            'price_max' => $price_max,
            'units' => 'metric',
            'temperature_unit' => 'c',
            'languagecode' => self::$langCode[$languagecode] ?? 'en-us',
            'currency_code' => $currency_code,
        ];
        $result = self::getRequestFile($url, $data, 10, $header);
        $result = json_decode($result, true);
        if (empty($result) || (!isset($result['data']['hotels']))) {
            return [];
        }
        $hotels = $result['data']['hotels'] ?? [];
        $hotel_list = [];
        foreach ($hotels as $key => $hotel) {
            //房间价格
            $grossPrice = $hotel['property']['priceBreakdown']['grossPrice']['value'];
            $excludedPrice = $hotel['property']['priceBreakdown']['excludedPrice']['value'];
            $grossPrice = Util::getAmountFormat($grossPrice, $currency_code);//货币格式化
            $excludedPrice = Util::getAmountFormat($excludedPrice, $currency_code);//货币格式化
            $hotel_list[] = [
                'hotel_id' => $hotel['hotel_id'],//酒店id
                'hotel_label' => $hotel['accessibilityLabel'],//酒店简介
                'hotel_name' => $hotel['property']['name'],//酒店名称
                'hotel_address' => $hotel['property']['wishlistName'],//地址
                'hotel_score' => $hotel['property']['reviewScore'],//评分
                'hotel_currency' => $hotel['property']['currency'],//货币
                'checkin' => $hotel['property']['checkin'],//入住时间
                'checkout' => $hotel['property']['checkout'],//退房时间
                'photoUrls' => $hotel['property']['photoUrls'],//图片
                'arrival_date' => $arrival_date,//入住日期
                'departure_date' => $departure_date,//退房日期
                'price' => round($grossPrice + $excludedPrice, 2),//总费用
                'gross_price' => round($grossPrice, 2),//酒店价格
                'excluded_price' => round($excludedPrice, 2),//其他费用
                'available_rooms' => rand(5, 10),//剩余房间数量
            ];
        }
        return $hotel_list;
    }

    /**
     * 获取酒店详情 房间列表
     * @param string $hotel_id
     * @param string $arrival_date
     * @param string $departure_date
     * @param int $room_qty
     * @param string $languagecode
     * @param string $currency_code
     * @return array
     */
    public static function getSearchHotelDetails($hotel_id = '', $arrival_date = '', $departure_date = '', $room_qty = 1, $languagecode = 'en', $currency_code = 'USD')
    {

        //curl --request GET
        //	--url 'https://booking-com15.p.rapidapi.com/api/v1/hotels/getHotelDetails?hotel_id=191605&arrival_date=2025-03-11&departure_date=2025-03-12&adults=1&children_age=1%2C17&room_qty=1&units=metric&temperature_unit=c&languagecode=en-us&currency_code=USD'
        //	--header 'x-rapidapi-host: booking-com15.p.rapidapi.com'
        //	--header 'x-rapidapi-key: 5678c90b20msh957d515fd3d2325p15200ejsncca27497f8eb'

        if (empty($arrival_date) || empty($departure_date)) {
            $arrival_date = date('Y-m-d', strtotime('+1 day'));
            $departure_date = date('Y-m-d', strtotime('+2 day'));
        }
        $url = 'https://' . self::$apiHost . '/api/v1/hotels/getHotelDetails';
        $header = [
            'x-rapidapi-host: ' . self::$apiHost,
            'x-rapidapi-key: ' . self::$apiKey
        ];
        $data = [
            'hotel_id' => $hotel_id,
            'arrival_date' => $arrival_date,
            'departure_date' => $departure_date,
            'adults' => $room_qty,
            'room_qty' => $room_qty,
            'children_age' => '1,17',
            'units' => 'metric',
            'temperature_unit' => 'c',
            'languagecode' => self::$langCode[$languagecode] ?? 'en-us',
            'currency_code' => $currency_code,
        ];
        $result = self::getRequestFile($url, $data, 10, $header);
        $result = json_decode($result, true);
        if (empty($result) || (!isset($result['data']))) {
            return [];
        }
        $hotel_temp = $result['data'] ?? [];
        //arrival_date:"2025-03-11"
        //departure_date:"2025-03-12"
        $hotel_info = [
            'hotel_id' => $hotel_temp['hotel_id'],//酒店id
            'hotel_name' => $hotel_temp['hotel_name'],//酒店名称
            'arrival_date' => $hotel_temp['arrival_date'],//入住时间
            'departure_date' => $hotel_temp['departure_date'],//退房时间
            'address' => $hotel_temp['address'],//地址
            'available_rooms' => $hotel_temp['available_rooms'],//剩余房间数量
        ];
        //curl --request GET
        //	--url 'https://booking-com15.p.rapidapi.com/api/v1/hotels/getRoomListWithAvailability?hotel_id=6152075&arrival_date=2025-03-11&departure_date=2025-03-12&adults=1&children_age=1%2C0&room_qty=1&units=metric&temperature_unit=c&languagecode=en-us&currency_code=USD'
        //	--header 'x-rapidapi-host: booking-com15.p.rapidapi.com'
        //	--header 'x-rapidapi-key: 5678c90b20msh957d515fd3d2325p15200ejsncca27497f8eb'

        $url = 'https://' . self::$apiHost . '/api/v1/hotels/getRoomListWithAvailability';
        $header = [
            'x-rapidapi-host: ' . self::$apiHost,
            'x-rapidapi-key: ' . self::$apiKey
        ];
        $data = [
            'hotel_id' => $hotel_id,
            'arrival_date' => $arrival_date,
            'departure_date' => $departure_date,
            'adults' => $room_qty,
            'children_age' => '1,17',
            'room_qty' => $room_qty,
            'units' => 'metric',
            'temperature_unit' => 'c',
            'languagecode' => self::$langCode[$languagecode] ?? 'en-us',
            'currency_code' => $currency_code,
        ];
        $result = self::getRequestFile($url, $data, 10, $header);
        $result = json_decode($result, true);
        if (empty($result) || (!isset($result['available']))) {
            return [];
        }
        $available_temp = $result['available'] ?? [];
        $available_arr = [];
        //currency:"USD"
        //amount_unrounded:"$197.10"
        //value:197.1047
        //amount_rounded:"$197"
        foreach ($available_temp as $available) {
//            if ($available['available'] == 1) {
            $gross_amount = Util::getAmountFormat($available['product_price_breakdown']['gross_amount']['value'], $currency_code);
            $excluded_amount = Util::getAmountFormat($available['product_price_breakdown']['excluded_amount']['value'], $currency_code);
            $available_arr[] = [
                'availableText' => $available['availableText'],//可用性文本描述
                'available' => $available['available'],//是否可用（1 表示可用）
                'full_board' => $available['full_board'],//是否包含全餐（0 表示不包含）
                'breakfast_included' => $available['breakfast_included'],//是否包含早餐（1 表示包含）
                'mealplan' => $available['mealplan'],//餐饮计划（例如："Breakfast included" 表示包含早餐）
                'name' => $available['name'],//房型名称（例如："Specialty Room - Free cancellation"）。
                'room_surface_in_m2' => $available['room_surface_in_m2'],//房间面积（平方米），例如：42 平方米
                'room_name' => $available['room_name'],//房型名称
                'max_occupancy' => $available['max_occupancy'],//最大入住人数
                'max_children_free' => $available['max_children_free'],//最大免费儿童
                'room_id' => $available['room_id'],//房型id
                'currency' => $available['product_price_breakdown']['all_inclusive_amount']['currency'],//货币代码（例如："USD"）
                'gross_amount' => round($gross_amount, 2),//房间价格（未四舍五入）
                'excluded_amount' => round($excluded_amount, 2),//税费
                'all_amount' => round($gross_amount + $excluded_amount, 2),//税费
            ];
//            }
        }
        $hotel_info['available_rooms_list'] = $available_arr;
        return $hotel_info;
    }

    /**
     * post 请求 curl 模式
     * @param $url
     * @param array $post_data
     * @param int $timeout
     * @param array $header
     * @return bool|string
     */
    public static function postRequestCurl($url, $post_data = array(), $timeout = 5, $header = [])
    {
        $header = empty($header) ? ['Content-Type:application/x-www-form-urlencoded', 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'] : $header;
        $post_string = http_build_query($post_data, '', '&');
        $ch = curl_init();    // 启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $url);     // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // 对认证证书来源的检查   // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);     // Post提交的数据包
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);     // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     // 获取的信息以文件流的形式返回
        try {
            $result = curl_exec($ch);
            curl_close($ch);
            return $result;
        } catch (Exception $exception) {
            curl_close($ch);
            return "";
        }
    }

    /**
     * get 请求 file_get_contents 模式
     * @param string $url
     * @param array $post_data
     * @param int $timeout
     * @param array $header
     * @return false|string
     */
    public static function getRequestFile($url = "", $post_data = [], $timeout = 60, $header = [])
    {
        if (empty($header)) {
            $header = 'Content-type:application/x-www-form-urlencoded';
        }
//        $header = ['Content-Type:application/x-www-form-urlencoded', 'User-Agent:Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.191 Safari/537.36'];
        //初始化
        $par = "";
        $i = 0;
        foreach ($post_data as $key => $value) {
            if ($i == 0) {
                if (strpos($url, '?') > 0) {
                    $par .= "&" . $key . "=" . $value;
                } else {
                    $par .= "?" . $key . "=" . $value;
                }
            } else {
                $par .= "&" . $key . "=" . $value;
            }
            $i++;
        }
        $options = array(
            'http' => array(
                'method' => 'GET',
                'timeout' => $timeout,
                'header' => $header,
            )
        );
        try {
            $context = stream_context_create($options);
            return @file_get_contents($url . $par, false, $context);
        } catch (Exception $exception) {
            return "";
        }

    }

}