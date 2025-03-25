<?php

class DateTimeHelper
{
    private $timezone;

    public function __construct($timezone = 'UTC')
    {
        // Set default timezone to UTC if not provided
        $this->timezone = new DateTimeZone($timezone);
    }

    // Get the start timestamp of the day based on the timezone passed
    public function getDayStartTimestamp($date = 'now')
    {
        $dateTime = new DateTime($date, $this->timezone);
        // Set time to 00:00:00 for the start of the day
        $dateTime->setTime(0, 0, 0);
        return $dateTime->getTimestamp();
    }

    // Get the end timestamp of the day based on the timezone passed
    public function getDayEndTimestamp($date = 'now')
    {
        $dateTime = new DateTime($date, $this->timezone);
        // Set time to 23:59:59 for the end of the day
        $dateTime->setTime(23, 59, 59);
        return $dateTime->getTimestamp();
    }

    // Get current timestamp in the provided timezone
    public function getCurrentTimestamp()
    {
        $dateTime = new DateTime('now', $this->timezone);
        return $dateTime->getTimestamp();
    }

    // Get formatted date based on the timezone passed
    public function getFormattedDate($format = 'Y-m-d H:i:s', $date = 'now')
    {
        $dateTime = new DateTime($date, $this->timezone);
        return $dateTime->format($format);
    }

    // Convert a given timestamp to a specific timezone and return formatted date
    public function convertTimestampToTimezone($timestamp, $format = 'Y-m-d H:i:s')
    {
        $dateTime = new DateTime("@$timestamp"); // Create DateTime from timestamp
        $dateTime->setTimezone($this->timezone); // Apply the timezone
        return $dateTime->format($format);
    }

    // New function to get the formatted date as dd-mm-yyyy 12:30:00 PM
    public function getCustomFormattedDate($date = 'now', $configTimeFormat)
    {
        $dateTime = new DateTime($date, $this->timezone);
        // Format: dd-mm-yyyy h:i:s A (12-08-2017 12:30:00 PM)
        return $dateTime->format($configTimeFormat);
    }
}

