<?php
/**
 * Created by PhpStorm.
 * User: fuzz
 * Date: 2020/12/15
 * Time: 10:49
 */

namespace Ccahouse\Ccahelper;


class DateUtil
{
    const SHOW_END_DATE = [9, 10, 11, 12, 13, 14, 16, 17, 18, 19, 20];


    static function getYear($date) {
        return date('Y', strtotime($date));
    }

    static function getMonth($date) {
        return date('m', strtotime($date));
    }

    /**
     * @param $start_m
     * @param $end_m
     * @return number
     * 计算两个日期相差月份
     */
    static function month_numbers($start_m, $end_m) {
        //日期格式为2018-8
        $date1 = explode('-', $start_m);
        $date2 = explode('-', $end_m);

        if ($date1[1] < $date2[1]) {
            $month_number = abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
        } else {
            $month_number = abs($date1[0] - $date2[0]) * 12 - abs($date1[1] - $date2[1]);
        }
        return $month_number;
    }


    /**
     * @param $date
     * @param int $i
     * @param int $type 0 月 1 月初 2月末
     * @return false|string
     * 获取指定日期提前n个月的日期
     */
    static function getDataCurrent($date, $i = 1, $type = 0) {
        if (!$date) {
            return '';
        }
        if ($i == 0) {
            return $date;
        }
        $timestamp = strtotime($date);
        if ($type == 0) {
            return date("Y-m", mktime(0, 0, 0, date("m", $timestamp) - $i + 1, 0, date("Y", $timestamp)));
        } elseif ($type == 1) {
            return date("Y-m-01", mktime(0, 0, 0, date("m", $timestamp) - $i + 1, 0, date("Y", $timestamp)));
        } elseif ($type == 2) {
            return date("Y-m-t", mktime(0, 0, 0, date("m", $timestamp) - $i + 1, 0, date("Y", $timestamp)));
        }
    }


    //获取前n个月的起始结束时间
    public static function getStartAndEndTime($i)
    {

        $begin_time = date("Y-m-d", mktime(0, 0, 0, date("m") - $i, 1, date("Y")));
        $end_time = date("Y-m-d", mktime(23, 59, 59, date("m") - ($i - 1), 0, date("Y")));

        return array($begin_time, $end_time);
    }


    /**
     * @param $start_m
     * @param $end_m
     * @return number
     * 计算两个日期相差月份
     */
    public static function monthNumbers($start_m, $end_m)
    { //日期格式为2018-8
        $startMonth = self::getMonth($start_m);
        $endMonth = self::getMonth($end_m);
        $startYear = self::getYear($start_m);
        $endYear = self::getYear($end_m);
        if ($startMonth < $endMonth) {
            $month_number = abs($startYear - $endYear) * 12 + abs($startMonth - $endMonth);
        } else {
            $month_number = abs($startYear - $endYear) * 12 - abs($startMonth - $endMonth);
        }
        return $month_number;
    }

    /**
     * User: fuzz
     * @param $start_time
     * @param $end_time
     * @param false $type 1 季度 2半年 3整年
     * @return false|int|string
     */
    public static function getDateType($start_time, $end_time, &$type = false)
    {
        $month_number = self::monthNumbers($start_time, $end_time);
        $showMonth = 0;
        $month = date('m', strtotime($end_time));
        $year = date('Y', strtotime($end_time));
        switch ($month_number) {
            case 2:
                $type = 1;
                $showMonth = $end_time;
                break;
            case 5:
                $type = 2;
                $showMonth = $end_time;
                break;
            case 11:
                $type = 3;
                if ($year == date('Y') && $month >= date('m')) {
                    $showMonth = date("Y-m", mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
                } else {
                    $showMonth = $end_time;
                }
                break;
        }
        return $showMonth;
    }

    public static function getAllMonth()
    {
        $year = date('Y');
        $month = date('m');
        $result = [];
        for ($i = 2017; $i <= $year; $i++) {
            for ($j = 1; $j <= 12; $j++) {
                if ($year == $i && $j > $month) {
                    continue;
                }
                $date = date('Y-m', mktime(0, 0, 0, $j, 1, $i));
                $result[] = $date;
            }
        }
        return $result;
    }


    /**
     * User: fuzz
     * @param $month
     * @param int $data
     * @return array
     *获取随访指表查询起始时间
     */
    static function getFollowCalculateDate($month, $data = 12)
    {

        $m = date('m', strtotime($month));
        $year = date('Y', strtotime($month));

        $a1 = 23;
        $a2 = 12;
        if ($data == 3) {
            $a1 = 15;
            $a2 = 4;
        } elseif ($data == 1) {
            $a1 = 13;
            $a2 = 2;
        } elseif ($data == 12) {
            $a1 = 24;
            $a2 = 13;
        }
        $start = date("Y-m-d", mktime(0, 0, 0, $m - $a1, 1, $year));
        $end = date("Y-m-t", mktime(0, 0, 0, $m - $a2, 1, $year));
        if ($data == 7) {
            $start = date("Y-m-d", mktime(0, 0, 0, $m - 12, -7, $year));
            $end = date("Y-m-d", mktime(0, 0, 0, $m, -7, $year));
        } elseif ($data == 13) {
            $start = date("Y-m-d", mktime(0, 0, 0, $m - 12, 1, $year));
            $end = date("Y-m-t", mktime(0, 0, 0, $m - 1, 1, $year));
        }

        return [$start, $end];
    }

    public static function monthRange($year)
    {
        if ($year > date('Y')) {
            return null;
        }
        $mon = array();
//      年度范围
        $arr = [
            'start_time' => $year . '-01',
            'end_time' => $year . '-12'
        ];
        $mon[] = $arr;
//      半年度范围
        $arr = [
            'start_time' => $year . '-01',
            'end_time' => $year . '-06'
        ];
        $mon[] = $arr;
        $arr = [
            'start_time' => $year . '-07',
            'end_time' => $year . '-12'
        ];
        $mon[] = $arr;
//      季度范围
        for ($i = 1; $i <= 4; $i++) {
            $arr = [
                'start_time' => $year . '-' . sprintf("%02d", ($i * 3) - 2),
                'end_time' => $year . '-' . sprintf("%02d", $i * 3)
            ];
            $mon[] = $arr;
        }
//        月份范围
        for ($i = 1; $i <= 12; $i++) {
            $i = sprintf("%02d", $i);
            $arr = [
                'start_time' => $year . '-' . $i,
                'end_time' => $year . '-' . $i
            ];

            $mon[] = $arr;
        }

        foreach ($mon as $key => &$item) {
            if ($year == date('Y') && self::getMonth($item['end_time']) > date('m')) {
                unset($mon[$key]);
                continue;
            }
            $item['start_time'] = date('Y-m', strtotime($item['start_time']));
            $item['end_time'] = date('Y-m', strtotime($item['end_time']));
        }
        return $mon;
    }

}
