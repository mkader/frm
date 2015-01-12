<?php

class Logger {
    static function log($message, $toStdout = false) {
        // Start with the timestamp
        $data = date("[Y-m-d H:i:s] ");

        // Append the message
        $data .= $message . "\n";

        // Output to stdout if true
        if ($toStdout) {
            echo $data;
        }

        // Append to the log file
		if (LOG_FILE) {
			$fp = fopen(LOG_FILE, 'a+');
			if ($fp) {
				fwrite($fp, $data);
				fclose($fp);
			}
		}
    }
}

?>