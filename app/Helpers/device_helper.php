<?php

if (!function_exists('waktuLalu')) {

    function waktuLalu($datetime)
    {
        if (!$datetime)
            return "-";

        $selisih = time() - strtotime($datetime);

        if ($selisih < 60)
            return $selisih . " detik lalu";

        if ($selisih < 3600)
            return floor($selisih / 60) . " menit lalu";

        if ($selisih < 86400)
            return floor($selisih / 3600) . " jam lalu";

        return floor($selisih / 86400) . " hari lalu";
    }
}