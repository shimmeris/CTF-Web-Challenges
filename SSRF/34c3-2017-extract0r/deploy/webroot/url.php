<?php
function in_cidr($cidr, $ip) {
    list($prefix, $mask) = explode("/", $cidr);

    return 0 === (((ip2long($ip) ^ ip2long($prefix)) >> (32-$mask)) << (32-$mask));
}

function get_port($url_parts) {
    if (array_key_exists("port", $url_parts)) {
        return $url_parts["port"];
    } else if (array_key_exists("scheme", $url_parts)) {
        return $url_parts["scheme"] === "https" ? 443 : 80;
    } else {
        return 80;
    }
}

function clean_parts($parts) {
    // oranges are not welcome here
    $blacklisted = "/[ \x08\x09\x0a\x0b\x0c\x0d\x0e:\d]/";

    if (array_key_exists("scheme", $parts)) {
        $parts["scheme"] = preg_replace($blacklisted, "", $parts["scheme"]);
    }

    if (array_key_exists("user", $parts)) {
        $parts["user"] = preg_replace($blacklisted, "", $parts["user"]);
    }

    if (array_key_exists("pass", $parts)) {
        $parts["pass"] = preg_replace($blacklisted, "", $parts["pass"]);
    }

    if (array_key_exists("host", $parts)) {
        $parts["host"] = preg_replace($blacklisted, "", $parts["host"]);
    }

    return $parts;
}

function rebuild_url($parts) {
    $url = "";
    $url .= $parts["scheme"] . "://";
    $url .= !empty($parts["user"]) ? $parts["user"] : "";
    $url .= !empty($parts["pass"]) ? ":" . $parts["pass"] : "";
    $url .= (!empty($parts["user"]) || !empty($parts["pass"])) ? "@" : "";
    $url .= $parts["host"];
    $url .= !empty($parts["port"]) ? ":" . (int) $parts["port"] : "";
    $url .= !empty($parts["path"]) ? "/" . substr($parts["path"], 1) : "";
    $url .= !empty($parts["query"]) ? "?" . $parts["query"] : "";
    $url .= !empty($parts["fragment"]) ? "#" . $parts["fragment"] : "";

    return $url;
}

function get_contents($url) {
    $disallowed_cidrs = [ "127.0.0.0/8", "169.254.0.0/16", "0.0.0.0/8",
        "10.0.0.0/8", "192.168.0.0/16", "14.0.0.0/8", "24.0.0.0/8", 
        "172.16.0.0/12", "191.255.0.0/16", "192.0.0.0/24", "192.88.99.0/24",
        "255.255.255.255/32", "240.0.0.0/4", "224.0.0.0/4", "203.0.113.0/24", 
        "198.51.100.0/24", "198.18.0.0/15",  "192.0.2.0/24", "100.64.0.0/10" ];

    for ($i = 0; $i < 5; $i++) {
        $url_parts = clean_parts(parse_url($url));

        if (!$url_parts) {
            error("Couldn't parse your url!");
        }

        if (!array_key_exists("scheme", $url_parts)) {
            error("There was no scheme in your url!");
        }

        if (!array_key_exists("host", $url_parts)) {
            error("There was no host in your url!");
        }

        $port = get_port($url_parts);
        $host = $url_parts["host"];

        $ip = gethostbynamel($host)[0];
        if (!filter_var($ip, FILTER_VALIDATE_IP, 
            FILTER_FLAG_IPV4|FILTER_FLAG_NO_PRIV_RANGE|FILTER_FLAG_NO_RES_RANGE)) {
            error("Couldn't resolve your host '{$host}' or 
                the resolved ip '{$ip}' is blacklisted!");
        }

        foreach ($disallowed_cidrs as $cidr) {
            if (in_cidr($cidr, $ip)) {
                error("That IP is in a blacklisted range ({$cidr})!");
            }
        }

        // all good, rebuild url now
        $url = rebuild_url($url_parts);


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 0);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($curl, CURLOPT_RESOLVE, array($host . ":" . $port . ":" . $ip)); 
        curl_setopt($curl, CURLOPT_PORT, $port);

        $data = curl_exec($curl);

        if (curl_error($curl)) {
            error(curl_error($curl));
        }

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status >= 301 and $status <= 308) {
            $url = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
        } else {
            return $data;
        }

    }

    error("More than 5 redirects!");
}
