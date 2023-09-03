<?php 

//This is where my helper functions live.

trait HelperFunctions {
    private function to_lower_snake_case($string): string {
        $string = preg_replace('/[\s-]+/', '_', $string);
        return strtolower($string);
    }

    public function pretty($data): void {
        $dataType = gettype($data);
        echo "<pre>";
        if ($dataType === 'array' || $dataType === 'object') {
            print_r($data);
        } else {
            var_dump($data);
        }
        echo "</pre>";
    }
}