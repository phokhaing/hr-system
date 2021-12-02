<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Modules\Payroll\Entities\PayrollSettings;

if (!function_exists('to_object')) {
    /**
     * Convert json string to object.
     *
     * @param $whatToConvert
     * @return mixed
     * @author Kimhai SAM <kimhai.sam@bronxtechnology.com>
     */
    function to_object($whatToConvert)
    {
        return is_object($whatToConvert) ? $whatToConvert : json_decode($whatToConvert);
    }
}

if (!function_exists('date_readable')) {
    /**
     * Date define save to DB or show ot user.
     *
     * @param null $date
     * @param bool $date_database
     * @return false|string
     * @author Ratana KHUN
     */
    function date_readable($date = null, $date_database = false)
    {
        if ($date === null) {
            return '';
        }
        return $date_database ?
            date('Y-m-d', strtotime($date)) :
            date('d-M-Y', strtotime($date));
    }
}

if (!function_exists('date_time_readable')) {
    /**
     * DateTime define save to DB or show ot user.
     *
     * @param null $dateTime
     * @param bool $date_database
     * @return false|string
     * @author Ratana KHUN
     */
    function date_time_readable($dateTime = null, $date_database = false)
    {
        if ($dateTime === null) {
            return '';
        }
        return $date_database ?
            date('Y-m-d H:i:s', strtotime($dateTime)) :
            date('d-M-Y h:i:s A', strtotime($dateTime));
    }
}

if (!function_exists('')) {
    /**
     * Duration of contract between start and end date are calculate to total in months
     * @param $startDate
     * @param $endDate
     * @return integer
     */
    function find_duration_contract($startDate, $endDate)
    {
        $strTimeStartDate = strtotime($startDate);
        $strTimeEndDate = strtotime($endDate);

        $yearStart = date('Y', $strTimeStartDate);
        $yearEnd = date('Y', $strTimeEndDate);

        $monthStart = date('m', $strTimeStartDate);
        $monthEnd = date('m', $strTimeEndDate);

        return (($yearEnd - $yearStart) * 12) + ($monthEnd - $monthStart);
    }
}

if (!function_exists('convertArrStrToArrJson')) {
    function convertArrStrToArrJson($arrStr)
    {
        $convertToArrayJsonObj = [];
        if ($arrStr != null) {
            for ($i = 0; $i < count($arrStr); $i++) {
                $string = $arrStr[$i];
                if (empty($string)) {
                    continue;
                }
                $convertToArrayJsonObj[$i] = to_object($string);
            }
        }
        return $convertToArrayJsonObj;
    }
}

if (!function_exists('appendArrJson')) {
    function appendArrJson($existingArr, $newJsonArr)
    {
        if ($newJsonArr != null) {
            for ($i = 0; $i < count($newJsonArr); $i++) {
                $string = $newJsonArr[$i];
                if (empty($string)) {
                    continue;
                }
                $existingArr[] = to_object($string);
            }
        }
        return $existingArr;
    }
}

if (!function_exists('saveImageFile')) {
    /**
     * https://www.codechief.org/article/laravel-6-ajax-image-upload-with-preview-using-base-64
     * @param $file
     * @param string $directory
     * @return string
     */
    function saveImageFile($file, string $directory)
    {
        $name = \Webpatser\Uuid\Uuid::generate(4);
        $path = storage_path() . '/app/public/' . $directory;
        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, TRUE);
        }

        $data = Image::make($file);
        $extension = $data->mime();
        $extension = preg_split("#/#", $extension);
        $data->save($path . '/' . $name . '.' . $extension[1]);

        return $directory . '/' . $name . '.' . $extension[1];
    }
}

if (!function_exists('saveFile')) {
    /**
     * Doc: https://laravel.com/docs/8.x/filesystem
     * @param $file
     * @param string $directory
     * @return string
     */
    function saveFile($file, string $directory)
    {
        $name = \Webpatser\Uuid\Uuid::generate(4);
        //        $path = storage_path() . '/app/public/' . $directory;
        //        if (!File::exists($path)) {
        //            File::makeDirectory($path, 0777, TRUE);
        //        }

        //        $extension = $file->getClientOriginalExtension();
        //        $file->storeAs($directory, $name . '.' . $extension);
        //        return $directory . '/' . $name . '.' . $extension;
        $name = \Webpatser\Uuid\Uuid::generate(4);
        $extension = $file->getClientOriginalExtension();
        $file->storeAs('public/' . $directory, $name . '.' . $extension);
        return $directory . '/' . $name . '.' . $extension;
    }
}

if (!function_exists('courseContentDirectory')) {
    /**
     * Director structure eg: course_content/course_id_and_course_title/images_or_sound/uuid_filename
     * @param $file
     * @param string $directory
     * @return string
     */
    function courseContentDirectory($course_id, $course_title, $prefix_path)
    {
        $replaceTitle = str_replace(' ', '_', $course_title);
        return strtolower(COURSE_CONTENT_BASE_PATH . $course_id . '_' . $replaceTitle . $prefix_path);
    }
}

if (!function_exists('courseContentDirectory')) {
    /**
     * Director structure eg: course_content/course_id_and_course_title/images_or_sound/uuid_filename
     * @param $file
     * @param string $directory
     * @return string
     */
    function courseContentDirectory($course_id, $course_title, $prefix_path)
    {
        $replaceTitle = str_replace(' ', '_', $course_title);
        return strtolower(COURSE_CONTENT_BASE_PATH . $course_id . '_' . $replaceTitle . $prefix_path);
    }
}

#Have the following method in your helper file
if (!function_exists('set_sql_mode')) {
    /**
     * @param string $mode
     * @return bool
     */
    function set_sql_mode($mode = '')
    {
        return \DB::statement("SET SQL_MODE=''");
    }
}

if (!function_exists('nb_mois')) {
    function nb_mois($date1, $date2)
    {
        $begin = new DateTime($date1);
        $end = new DateTime($date2);
        $end = $end->modify('+1 month');

        $interval = DateInterval::createFromDateString('1 month');

        $period = new DatePeriod($begin, $interval, $end);
        $counter = 0;
        foreach ($period as $dt) {
            $counter++;
        }

        return $counter;
    }
}

if (!function_exists('checkIfAvilableToDeleteContract')) {
    /**
     * Staff that available to block salary (contract) must in active and current contract
     */
    function checkIfAvilableToDeleteContract($contract)
    {
        $contractObj = to_object(@$contract->contract_object);
        $contractLastDate = @$contractObj->contract_last_date ? Carbon::parse(@@$contractObj->contract_last_date) : Carbon::now();
        $now = Carbon::now();
        return @$contractLastDate->greaterThan($now) || @$contractLastDate->equalTo($now);
    }
}


