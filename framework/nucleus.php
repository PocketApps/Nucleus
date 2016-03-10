<?php

class nucleus {
    /**
     * Initializes the Nucleus test
     */
    public static function init() {
        echo "<h1>Test Results</h1><h3>" . date('d F Y (H:i:s)') . "</h3>";
    }

    /**
     * Initializes the Nucleus test and applies css stylesheets to the document
     * @param string (or string array) $cssFiles
     * The css stylesheet(s) to apply to the document
     */
    public static function init_styled($cssFiles) {
        foreach ($cssFiles as $cssFile) {
            echo "<link href='$cssFile' rel='stylesheet' type='text/css'>";
        }
        echo "<h1>Test Results</h1><h3>" . date('d F Y (H:i:s)') . "</h3>";
    }

    /**
     * Creates a new network related test and echo the content to the document
     * @param string $title
     * The display name used for your website e.g. Facebook
     * @param string $url
     * The url that should be used for the network test e.g. https://www.facebook.com
     */
    public static function network_tests($title, $url) {
        $item1 = self::table_network_result(1, $title, $url);
        $item2 = self::table_network_result(2, "Google", "https://www.google.com");
        $item3 = self::table_network_result(3, "Facebook", "https://www.facebook.com");
        $item4 = self::table_network_result(4, "YouTube", "https://www.youtube.com");
        $item5 = self::table_network_result(5, "Amazon", "https://www.amazon.com");
        $item6 = self::table_network_result(6, "Twitter", "https://www.twitter.com");
        echo "<hr/><h4><b><u>Network Tests</u></b></h4>
        <table class='table table-striped table-hover'>
            <thead><tr><th>#</th><th>Website</th><th>Url</th><th>Response Time</th><th>Online</th></tr></thead>
            <tbody>$item1$item2$item3$item4$item5$item6</tbody>
        </table>";
    }

    /**
     * Internal method used to generate network related test content in HTML
     * @param int $index
     * The index that should be displayed in the first column
     * @param string $title
     * The display name used for the website e.g. Facebook
     * @param string $url
     * The url that should be used for the network test e.g. https://www.facebook.com
     * @return string
     * Returns the generate HTML content
     */
    private static function table_network_result($index, $title, $url) {
        $urlParse = parse_url($url);
        $internalUrl = $urlParse['host'];

        $pingResult = self::ping($internalUrl);

        $isOnline = $pingResult !== -1;
        $isOnlineClass = $isOnline ? "success" : "danger";
        $isOnlineText = $isOnline ? "Yes" : "No";

        return "<tr class='$isOnlineClass'>
                <td>$index</td>
                <td>$title</td>
                <td><a href='$url' target='_blank'>$url</a></td>
                <td>$pingResult ms</td>
                <td>$isOnlineText</td>
            </tr>";
    }

    /**
     * Internal method used to ping a website and return the response time
     * @param string $url
     * The url that should be pinged e.g. https://www.facebook.com
     * @return int
     * Returns the response time (Returns -1 if the site is down)
     */
    private static function ping($url){
        $starttime = microtime(true);
        $file = @fsockopen($url, 80, $errno, $errstr, 10);
        $stoptime = microtime(true);
        $status = 0;

        if (!$file){
            $status = -1;  // Site is down
        } else {
            fclose($file);
            $status = ($stoptime - $starttime) * 1000;
            $status = floor($status);
        }
        return $status;
    }

    /**
     * Create a new API test and echo the content to the document
     * @param string $title
     * The display name used for your API e.g. Authentication
     * @param string $description
     * A short description for the API
     * @param string $tests
     * An array of JSON strings used to run tests. Please use the create_test method to generate new tests
     */
    public static function api_test($title, $description, $tests) {
        echo "<hr/><h4><b><u>$title</u></b></h4><h5>$description</h5>";
        echo "<table class='table table-striped table-hover'>
            <thead><tr><th>#</th><th>Title</th><th>Url</th><th>Response Time</th><th>Test passed</th></tr></thead><tbody>";

        $index = 1;
        foreach ($tests as $test) {
            $json = json_decode($test);
            echo self::table_test_result($index, $json->{"title"}, $json->{"url"}, $json->{"data"}, $json->{"expected"},
                $json->{"value"}, $json->{"type"});
            $index++;
        }

        echo "</tbody></table>";
    }

    /**
     * Create a single JSON string representing an API test
     * @param string $title
     * The display name used for your API e.g. Authenticating without a password
     * @param string $url
     * The url that should be used to test the API
     * @param string $data
     * The data being passed to the API
     * @param string $expectedElement
     * The JSON element that you are expecting in the response
     * @param string $expectedResult
     * The value that you are expecting in the response
     * @param string $requestType
     * The type of request being performed: GET or POST
     * @return string
     * Returns a new JSON representation of an API test
     */
    public static function create_test($title, $url, $data, $expectedElement, $expectedResult, $requestType) {
        return json_encode(array(
            "title" => $title,
            "url" => $url,
            "data" => json_encode($data),
            "expected" => $expectedElement,
            "value" => $expectedResult,
            "type" => $requestType
        ));
    }

    /**
     * Internal method used to generate API related test content in HTML
     * @param int $index
     * The index that should be displayed in the first column
     * @param string $title
     * The display name used for the website e.g. Authenticating without a password
     * @param string $url
     * The url that should be used to test the API
     * @param string $data
     * The data being passed to the API. This should be in Json format
     * @param string $expectedElement
     * The JSON element that you are expecting in the response
     * @param string $expectedResult
     * The value that you are expecting in the response
     * @param string $requestType
     * The type of request being performed: GET or POST
     * @return string
     * Returns the generate HTML content
     */
    private static function table_test_result($index, $title, $url, $data, $expectedElement, $expectedResult, $requestType) {
        $json = json_decode(self::run($url, json_decode($data), $expectedElement, $expectedResult, $requestType));

        $passedClass = $json->{"passed"} ? "success" : "danger";
        $passedText = $json->{"passed"} ? "Yes" : "No";
        $responseTime = $json->{"time"};

        return "<tr class='$passedClass'>
                <td>$index</td>
                <td>$title</td>
                <td><a href='$url' target='_blank'>$url</a></td>
                <td>$responseTime ms</td>
                <td>$passedText</td>
            </tr>";
    }

    /**
     * Internal method used to test an API
     * @param string $url
     * The url that should be used to test the API
     * @param string $data
     * The data being passed to the API
     * @param string $expectedElement
     * The JSON element that you are expecting in the response
     * @param string $expectedResult
     * The value that you are expecting in the response
     * @param string $requestType
     * The type of request being performed: GET or POST
     * @return string
     * Returns the test result
     */
    private static function run($url, $data, $expectedElement, $expectedResult, $requestType) {
        $start = microtime(true);
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            switch ($requestType) {
                case "GET":
                    curl_setopt($ch, CURLOPT_POST, false);
                    $url = self::build_url($url, $data);
                    break;
                case "POST":
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    break;
                default:
                    return json_encode(array(
                        "passed" => true,
                        "time" => floor((microtime(true) - $start) * 1000)
                    ));
            }

            curl_setopt($ch, CURLOPT_URL, $url);
            $response = curl_exec($ch);

            if (empty($response)) {
                throw new Exception("Empty response received");
            }

            $json = json_decode($response);
            if ($json->{$expectedElement} !== $expectedResult) {
                throw new Exception("Test did not pass");
            }

            return json_encode(array(
                "passed" => true,
                "time" => floor((microtime(true) - $start) * 1000)
            ));
        } catch (Exception $ex) {
            return json_encode(array(
                "passed" => false,
                "time" => floor((microtime(true) - $start) * 1000)
            ));
        }
    }

    private static function build_url($baseUrl, $data) {
        $url = $baseUrl . "?";
        foreach ($data as $key => $value) {
            $url .= $key . "=" . $value . "&";
        }
        return substr($url, 0, strlen($url)-1);
    }
}