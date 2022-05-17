<?php
function asset_js($paths)
{
    if (!is_iterable($paths)) {
        $paths = [$paths];
    }

    $content = '';
    foreach ($paths as $_path) {
        $content .= sprintf('<script src="%s"></script>',
            asset($_path) . '?v=' . filemtime(public_path($_path))
        );
    }

    return $content;
}

function asset_css($paths)
{
    if (!is_iterable($paths)) {
        $paths = [$paths];
    }
    $content = '';
    foreach ($paths as $_path) {
        $content .= sprintf('<link href="%s" rel="stylesheet">',
            asset($_path) . '?v=' . filemtime(public_path($_path))
        );
    }

    return $content;
}

function errors2message($errors)
{
    $message = [];
    foreach ($errors as $err => $m) {
        $message[] = $m;
    }
    return implode('<br>', $message);
}

function d1($var)
{
    echo "<pre>";
    echo json_encode($var, 128);
    echo "</pre>";
    die;
}

function word_normalized($word)
{
    $word = preg_replace('/[\-_]/', ' ', $word);
    return ucwords($word);
}

function date_parse_range($date_str)
{
    if (!is_string($date_str)) {
        return false;
    }

    $tmp = explode('_', $date_str);
    if (count($tmp) >= 2) {
        list($start, $end) = $tmp;
        $start = date('Y-m-d', strtotime($start));
        $end = date('Y-m-d', strtotime($end));
        return compact('start', 'end');
    }


    return false;
}

function columns2rules($col)
{
    /**
     * 0 => {#726
     * +"Field": "id"
     * +"Type": "int(10) unsigned"
     * +"Null": "NO"
     * +"Key": "PRI"
     * +"Default": null
     * +"Extra": "auto_increment"
     * }
     */
    $rules = [];
    if ($col->Null === 'NO') {
        $rules[] = 'required';
    }

    if (preg_match('/^(int|varchar|tinyint)\((\d+)/', $col->Type, $m)) {

        if (isset($m[1]) && $m[1] === 'int' || $m[1] === 'tinyint' || $m[1] === 'bigint') {
            $rules[] = 'numeric';
        }
        if ($m[1] === 'varchar' && isset($m[2])) {
            $rules[] = 'max:' . $m[2];
        }
    } else if ($col->Type === 'date') {
        $rules[] = 'date_format:Y-m-d';
    } else if ($col->Type === 'timestamp' || $col->Type === 'datetime') {
        $rules[] = 'date_format:Y-m-d H:i:s';
    }

    return implode('|', $rules);
}

function mail_send($to, $subject, $content)
{
    $SSOHelper = new \App\Helpers\SSOHelper();
    $params = [
        'mail' => $to,
        "subject" => $subject,
        'content' => $content,
        'type' => 'raw',
    ];

    return $SSOHelper->requestPost('/api/sendmail', [
        'form_params' => $params
    ], true);
}


function timeAgo($timestamp = 0, $now = 0): string
{

    // Set up an array of time intervals.
    $intervals = array(
        60 * 60 * 24 * 365 => 'Năm',
        60 * 60 * 24 * 30 => 'Tháng',
        60 * 60 * 24 * 7 => 'Tuần',
        60 * 60 * 24 => 'Ngày',
        60 * 60 => 'Giờ',
        60 => 'Phút',
        1 => 'Giây',
    );

    // Get the current time if a reference point has not been provided.
    if (0 === $now) {
        $now = time();
    }

    // Make sure the timestamp to check predates the current time reference point.
    if ($timestamp > $now) {
        throw new \Exception('Timestamp postdates the current time reference point');
    }

    // Calculate the time difference between the current time reference point and the timestamp we're comparing.
    $time_difference = (int)abs($now - $timestamp);

    if ($time_difference > 2592000) {
        return date('d/m/Y H:i', $timestamp);
    }

    if ($time_difference < 60) {
        return 'Vừa xong';
    }
    // Check the time difference against each item in our $intervals array. When we find an applicable interval,
    // calculate the amount of intervals represented by the the time difference and return it in a human-friendly
    // format.
    foreach ($intervals as $interval => $label) {

        // If the current interval is larger than our time difference, move on to the next smaller interval.
        if ($time_difference < $interval) {
            continue;
        }

        // Our time difference is smaller than the interval. Find the number of times our time difference will fit into
        // the interval.
        $time_difference_in_units = round($time_difference / $interval);

        if ($time_difference_in_units <= 1) {
            $time_ago = sprintf('1 %s trước',
                $label
            );
        } else {
            $time_ago = sprintf('%s %s trước',
                $time_difference_in_units,
                $label
            );
        }

        return $time_ago;
    }

    return 'Unknown';
}

function front_url($path)
{
    return config('app.front_url') . '/' . ltrim($path, '/');
}

function curl_get_json($url, $query = null, $basicAuth = null)
{
    $res = curl_get($url, $query, $basicAuth);
    return json_decode($res, true);
}


function curl_get($url, $query = null, $basicAuth = null)
{

    if (is_array($query)) {
        $tmp = explode('?', $url);
        $url = $tmp[0];
        if (isset($tmp[1])) {
            parse_str($tmp[1], $cQuery);
            $query = array_merge($cQuery, $query);
        }
        $url .= '?' . http_build_query($query);
    }


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36");

    if ($basicAuth) {
        curl_setopt($ch, CURLOPT_USERPWD, $basicAuth);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
// receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $server_output = curl_exec($ch);

    if ($server_output === false) {
        trigger_error(curl_error($ch));
    }


    curl_close($ch);
    /// file_put_contents($filename, $server_output);
    return $server_output;
}

function curl_post($url, $data = array(), $jsonContentType = false)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);

    if ($jsonContentType) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    } else {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    //  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

// in real life you should use something like:
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//          http_build_query(array('postvar1' => 'value1')));

// receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);
    return $server_output;
}

function generate_id($url)
{
    return trim(str_replace('/', '_', $url), '_');
}

function image_url($path)
{
    return config('app.image_url') . '/' . ltrim($path, '/');
}

/**
 * @return \App\Models\User
 */
function auth_user()
{
    return auth()->user();
}

function googleClientId(): string
{
    static $clientID;
    if ($clientID) {
        return $clientID;
    }

    $clientID = config('services.google.client_id');

    return $clientID;
}

function vue(array $vars = [], array $jsonData = [])
{
    $vars['jsonData'] = $jsonData;

    return view('admin.layouts.vue', $vars);
}

function get_gravatar(string $id, $s = 128, $d = 'identicon', $r = 'g', $img = false, $atts = array()): string
{
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5(strtolower(trim($id)));
    $url .= "?s=$s&d=$d&r=$r";
    if ($img) {
        $url = '<img src="' . $url . '"';
        foreach ($atts as $key => $val)
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}

function component(string $component, array $jsonData = [])
{
    $vars['jsonData'] = $jsonData;
    $vars['component'] = $component;

    return view('admin.layouts.vue', $vars);
}

function get_virtual_path($physical_path)
{
    $physical_path = str_replace('\\', '/', $physical_path);
    $path = str_replace(env('APP_URL'), "", $physical_path);

    return $path;

}

function jwtToken($payload)
{
    $header = [
        "alg" => "HS256",
        "typ" => "JWT"
    ];

    $data = base64_encode(json_encode($header)) . "." . base64_encode(json_encode($payload));
    $hashedData = hash_hmac('sha256',$data, env('SECRET_KEY'));
    $signature = base64_encode($hashedData);

    return $data. ".".$signature;
}
