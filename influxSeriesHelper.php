<?php

class influxSeriesHelper {
    private $config_data;

    public function __construct($json) {
        $this->config_data = $json;
    }

    private function restructure($dpIdentifier){
        foreach($dpIdentifier as $index => $keyvalue){
            $influx_result_series_like_tags[$keyvalue['key']] =  $keyvalue['value'];
        }
        return $influx_result_series_like_tags;
    }


    public function findSeriesIndexFromJson($dpIdentifier, $influx_results) {
        foreach ($influx_results['results'] as $statement_id => $statements) {
            if(count($statements['series']) != 0){
                foreach ($statements['series'] as $series) {
                    //print_r($series['tags']);
                    //Array ( [did] => Inverter02 [dlid] => 9C956E78E37A [f] => AC_Active_Power )

                    //print_r($dpIdentifier);
                    //Array ( [0] => Array ( [key] => dlid [value] => 9C956E78E37A ) [1] => Array ( [key] => did [value] => Inverter01 ) [2] => Array ( [key] => f [value] => AC_Active_Power ) )
                    if($this->restructure($dpIdentifier)==$series['tags']){
                       return $series;
                    }



                }
            }
        
        }
    }


}

?>
