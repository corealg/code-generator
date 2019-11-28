<?php

use \Firebase\JWT\JWT;

function message($message, $type = "success", $close_button = true)
{
    if ($type === "success") {
        $class = "success";
        $icon = "check-circle";
    } elseif ($type === "warning") {
        $class = "warning";
        $icon = "error";
    } elseif ($type === "error") {
        $class = "danger";
        $icon = "error";
    } elseif ($type === "info") {
        $class = "info";
        $icon = "info-circle";
    } else {
        $class = "success";
        $icon = "check-circle";
    }

    if ($close_button === true) {
        $close_button_html = "
            <button type='button' class='close' data-dismiss='alert'>
                <span aria-hidden='true'>×</span>
                <span class='sr-only'>Close</span>
            </button>
        ";
    } else {
        $close_button_html = "";
    }

    $html = "
        <div class='alert alert-$class mb-2'>
            $close_button_html
            <i class='bx bxs-$icon'></i> $message
        </div>
    ";

    return $html;
}

/**
 * We will use this function for styling validation error message
 * @param string
 * @return string
 */
function validation_error($message, $elementId = '', $optional = false)
{
    if ($message == '' && $optional == true) {
        return '';
    }
    $myMessage = $message == "" ? "*" : "[$message]";
    $elmId = $elementId != '' ? "id=ve-" . trim($elementId) : '';
    return "<small $elmId class='validation-error'>$myMessage</small>";
}

function validationHintsMessage()
{
    return "<small class='validation-hints'><i class='fa fa-info-circle'></i> All fields marked with an asterisk (*) are required.</small>";
}

/**
 * Display pagination summery
 *
 * @param int $total_data
 * @param int $data_per_page
 * @param int $current_page
 */
function get_pagination_summary($data)
{

    $total_item = $data->total();
    $item_per_page = $data->perPage();
    $current_page = $data->currentPage();

    $pagination_summary = "";
    if ($total_item > $item_per_page) {
        if ($current_page == 1) {
            $pagination_summary = "Showing 1 to $item_per_page records of $total_item";
        } else {
            if (($total_item - $current_page * $item_per_page) > $item_per_page) {
                $from = ($current_page - 1) * $item_per_page + 1;
                $to = $current_page * $item_per_page;
                $pagination_summary = "Showing $from to $to records of $total_item";
            } else {
                $from = ($current_page - 1) * $item_per_page + 1;
                $to = ($total_item - ($current_page - 1) * $item_per_page) + ($current_page - 1) * $item_per_page;
                $pagination_summary = "Showing $from to $to records of $total_item";
            }
        }
    }
    return $pagination_summary;
}

/**
 * Description: This function will return app build info
 * @return string App Build Info
 */
function app_build_info()
{
    $build_path = base_path('build.json');
    if (file_exists($build_path)) {
        $file_handle = fopen($build_path, "r");
        $build_info_data = fread($file_handle, filesize($build_path));
        fclose($file_handle);
        $build_info = json_decode($build_info_data, true);
        if (is_array($build_info)) {
            $output = "";
            if (array_key_exists('build_number', $build_info) && array_key_exists('build_date', $build_info)) {
                $output .=  "v" . $build_info["build_number"] . "." . $build_info["build_date"];
            }
            if (array_key_exists('build_branch', $build_info) && !empty($build_info["build_branch"])) {
                $output .= " | Branch: " . $build_info["build_branch"];
            }

            return $output;
        }
    }
}

function buildToken(array $data, $validity = 3600)
{
    // set token issue at time
    $iat = Carbon::now()->timestamp;

    // set expiry
    $exp = $iat + $validity;

    // prepare token so user will able to re-login from lockscreen
    $token = JWT::encode([
        "iss" => env('APP_HOST'),
        "iat" => $iat,
        "exp" => $exp,
        "data" => $data
    ], env('JWT_SECRET'));

    return $token;
}

function parseToken(string $token)
{
    $decodedToken = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

    return $decodedToken->data;
}

function getProfilePhoto($user)
{
    //dd($user->isDealer());
    if ($user->isDealer() === true) {
        $path  = $user->dealer->photo;
    } else {
        $path  = "storage/{$user->employee->photo}";
    }

    if (is_null($path) === true) {
        $path = config('constants.default.profile-photo');
    }

    return url($path);
}

function number2word($number)
{
    $numberToWords = new \NTWIndia\NTWIndia();

    $word = $numberToWords->numToWord($number);

    return $word . ' Taka Only.';
}

function buildExportHash($data)
{
    $token = buildToken($data);
    $hash = md5(auth()->user()->id);
    session()->put($hash, $token);

    return $hash;
}

function parseExportHash($hash)
{
    $token = session()->get($hash);

    return parseToken($token);
}
