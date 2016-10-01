<?php

namespace HqEngine\HqTool;

class HqUtil {

    public static function checkFile($path, $dir = false)
    {
        if ($dir)
        {
            self::existDir($path);
        }
        else
        {
            $dir = dirname($path);
            self::existDir($dir);
            self::existFile($path);
        }
    }

    private static function existDir($path)
    {
        if (!is_dir($path))
        {
            mkdir($path, 0755, true);
        }
    }

    private static function existFile($path)
    {
        if (!file_exists($path))
        {
            $file = fopen($path, "w");
            fclose($file);
        }
    }

    public static function checkPermission($require_permission = "", $list_permission = "")
    {
        $check_admin = strpos($list_permission, "admin");
        if (is_numeric($check_admin))
        {
            return true;
        }
        else
        {
            $check_require_permission = strpos($list_permission, $require_permission);
            if (is_numeric($check_require_permission))
            {
                return true;
            }
        }
        return false;
    }

    public static function checkPhone($phone)
    {
        $pattern_1 = "/^0[0-9]{9}$/";
        $pattern_2 = "/^0[0-9]{10}$/";
        if (preg_match($pattern_1, $phone) || preg_match($pattern_2, $phone))
        {
            return true;
        }
        return false;
    }

    public static function mergeChartDateRange($data, $date_start, $date_end, $key_data = 'value', $key_date = 'time')
    {
        $array_date = array();
        $total      = 0;
        if (!empty($data))
        {
            foreach ($data as $item)
            {
                $time                 = explode(' ', $item[$key_date]);
                $array_date[$time[0]] = intval($item[$key_data]);
                $total +=intval($item[$key_data]);
            }
        }
        $array_data     = self::createDateRangeArray($date_start, $date_end);
        $array_final    = array_merge($array_data, $array_date);
        $array_category = array();
        foreach ($array_final as $date => $value)
        {
            $array_category[] = date("d", strtotime($date));
        }
        $response["data"]       = $array_final;
        $response["total"]      = $total;
        $response["data_chart"] = implode(",", array_values($array_final));
        $response["date_chart"] = implode(",", $array_category);
        return $response;
    }

    public static function mergeStatisticAverage($array, $month = false, $year = false)
    {
        $array_date = array();
        $total      = 0;
        if (!empty($array))
        {
            foreach ($array as $item)
            {
                $time = explode(' ', $item['time']);
                if ($transfer)
                {
                    $item['value'] = round(($item['value'] / ($money_to_coins)) / 1000);
                }
                $array_date[$time[0]] = intval($item['value']);
                $total +=intval($item['value']);
            }
        }
        $statistic             = Util::createStatisticArray($month, $year);
        $statistic             = array_merge($statistic, $array_date);
        ksort($statistic);
        $response["statistic"] = $statistic;
        if ($year < date("Y"))
        {
            $response["total"] = round($total / count($response["statistic"]));
        }
        else
        {
            if ($month == date("m"))
            {
                $response["total"] = round($total / date("d"));
            }
            else
            {
                $response["total"] = round($total / count($response["statistic"]));
            }
        }
        return $response;
    }

    public static function createStatisticData($array_statistic)
    {
        $array_date  = array();
        $array_value = array();
        if (!empty($array_statistic))
        {
            foreach ($array_statistic["statistic"] as $date => $value)
            {
                $date          = explode("-", $date);
                $array_date[]  = $date[2];
                $array_value[] = $value;
            }
        }
        return array(
            "date"   => implode(",", $array_date),
            "values" => implode(",", $array_value),
            "total"  => ($array_statistic["total"])
        );
    }

    public static function createDateArray($month, $year)
    {
        $arr         = array();
        $m           = $month < 10 ? '0' . intval($month) : $month;
        $number_days = self::cal_days_in_month($month, $year);
        for ($i = 1; $i <= $number_days; $i++)
        {
            $d                                = $i < 10 ? '0' . intval($i) : $i;
            $arr[$year . '-' . $m . '-' . $d] = '0';
        }
        return $arr;
    }

    public static function createDateRangeArray($date_start, $date_end)
    {
        $date_range = self::getRangeDate($date_start, $date_end);
        $data_value = array();
        foreach ($date_range as $date)
        {
            $data_value[$date] = 0;
        }
        return $data_value;
    }

    public static function getRangeDate($first, $last, $output_format = 'Y-m-d', $step = '+1 day')
    {

        $dates   = array();
        $current = strtotime($first);
        $last    = strtotime($last);

        while ($current <= $last)
        {

            $dates[] = date($output_format, $current);
            $current = strtotime($step, $current);
        }

        return $dates;
    }

    public static function _encrypt($data_string, $key)
    {
        $data_base = base64_encode($data_string);
        $key_base  = md5($data_base . $key);
        return $data_base . "." . $key_base;
    }

    public static function _decrypt($data, $key)
    {
        $blank     = explode(".", $data);
        $data_base = $blank[0];
        $key_base  = $blank[1];
        $key_check = md5($data_base . $key);
        if ($key_base != $key_check)
        {
            return false;
        }
        return base64_decode($data_base);
    }

}
