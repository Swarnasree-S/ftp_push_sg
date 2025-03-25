<?php

class QueryGenerator {
    private $data;

    public function __construct($json,$start_time_filter,$end_time_filter) {
        $this->data = $json;
        $this->start_time_filter = $start_time_filter;
        $this->end_time_filter = $end_time_filter;
    }

    public function generateQueries() {
        // Initialize an array to store queries by measurement
        $queriesByMeasurement = [];

        foreach ($this->data['dataPoints'] as $dataPoint) {
            $measurement = $dataPoint['measurement'];
            $conditions = [];
            foreach ($dataPoint['dp'] as $dp) {
                $conditions[] = '"' . $dp['key'] . '"=\'' . $dp['value'] . '\'';
                $groupbyKeys[] = $dp['key']; 
            }
            $whereClause = '(' . implode(' AND ', $conditions) . ')';

            // Initialize the measurement array if not already done
            if (!isset($queriesByMeasurement[$measurement])) {
                $queriesByMeasurement[$measurement] = [];
            }

            // Add the condition to the respective measurement array
            $queriesByMeasurement[$measurement][] = $whereClause;
        }

        // Generate queries
        $queries = [];
        foreach ($queriesByMeasurement as $measurement => $conditions) {
            $whereClause = implode(' OR ', $conditions);
            $queries[] = 'SELECT mean(value) FROM ' . $measurement . ' WHERE ' . $whereClause . ' AND time > '.$this->start_time_filter. ' AND time < '.$this->end_time_filter.' GROUP BY '.implode(",",$groupbyKeys).',time(15m) tz(\'Asia/Kolkata\');';
        }

        return $queries;
    }
}

?>
