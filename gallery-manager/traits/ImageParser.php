<?php
trait ImageParser {
    public function parse_raw_data(string $raw_data): array {
        $parsed_data = [];
        $lines = explode(',', $raw_data);

        if (count($lines) > 0) {
            $out = [];
            $fodder = [];
            $current = '';

            foreach ($lines as $line) {
                if (strpos($line, ':')) {
                    list($key, $value) = explode(':', $line);
                    $current = $this->to_lower_snake_case(trim($key));
                    $fodder[$current] = [$value];
                } else {
                    $fodder[$current][] = $line;
                }
            }

            foreach ($fodder as $key => $line) {
                $out[$key] = implode(',', $fodder[$key]);
            }

            $fodder = $out;
            $out = [];

            foreach ($fodder as $key => $line) {
                if ($key == 'prompt' || $key == 'negative_prompt') {
                    $out['top'][$key] = $line;
                } else {
                    if (strlen($line)) {
                        $out['json'][$key] = $line;
                    }
                }
            }

            $out['json'] = json_encode($out['json']);
            $fodder = [];
        }
        return $out;
    }
}