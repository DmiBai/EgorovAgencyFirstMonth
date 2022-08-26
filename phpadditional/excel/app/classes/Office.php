<?php

require 'Driver.php';

class Office
{
    private int $everydayProd;
    private array $drivers;
    private int $startDate;
    private int $workDaysNum;

    /**
     * Office constructor.
     * @param int $everydayProd
     * @param int $workDayStartTime
     * @param int $workDayEndTime
     */
    public function __construct(int $everydayProd = 300, int $startDate = 44795)
    {
        $this->everydayProd = $everydayProd;
        $this->startDate = $startDate;
        $this->drivers = array();
        $this->workDaysNum = 5;
    }

    /**
     * @param array $drivers
     */
    public function addDriver(Driver $driver): void
    {
        $id = count($this->drivers);
        array_push($this->drivers, $id);
        $this->drivers[$id] = $driver;
    }

    /**
     * @param array $drivers
     */
    public function setDrivers(array $drivers): void
    {
        foreach ($drivers as $driver) {
            $this->addDriver($driver);
        }
    }

    public function makeDailySheet(): array
    {
        $dailySheet = array();
        $todayProd = 0;

        foreach ($this->drivers as $driver) {
            $dailySheet[$driver->getName()] = 0;
        }

        while ($todayProd < $this->everydayProd) {
            $minDriverID = $this->getMinHoursWeekID();
            $minDriver = $this->drivers[$minDriverID];

            if ($minDriver->getHoursToday() < $minDriver->getHours()) {
                $minProd = $minDriver->getProd();// * $minDriver->getHours();
                $minDriver->setDoneToday($minProd);
                $todayProd += $minProd;
            }

            $minDriverName = $this->drivers[$minDriverID]->getName();
            if ($minProd) {
                $dailySheet[$minDriverName] += $minProd / $this->drivers[$minDriverID]->getProd();
            }
        }


        foreach ($this->drivers as $driver) {
            $driver->resetDoneToday();
        }

        return $dailySheet;
    }

    public function makeWeeklySheet(): array
    {
        $weeklySheet = array();

        for ($day = 0; $day < $this->workDaysNum; $day++) {
            $weeklySheet[$day] = $this->makeDailySheet();
        }

        $readableSheet = array();
        $day = 1;
        foreach ($this->drivers as $driver) {
            $driverName = $driver->getName();
            $onePersonSheet = array();
            foreach ($weeklySheet as $item) {
                array_push($onePersonSheet, $item[$driverName]);
            }
            $readableSheet[$driverName] = $onePersonSheet;
        }
        return $readableSheet;
    }

    public function getMinDoneYesterday(): Driver
    {
        $min = $this->drivers[0];
        foreach ($this->drivers as $driver) {
            if ($driver->getDoneYesterday() < $min->getDoneYesterday()) {
                $min = $driver;
            }
        }
        return $min;
    }

    public function getMinDoneTodayID(): int
    {
        $minID = 0;
        foreach ($this->drivers as $id => $driver) {
            if ($driver->getDoneToday() < $this->drivers[$minID]->getDoneToday()) {
                $minID = $id;
            }
        }
        return $minID;
    }

    public function getMinDoneWeekID(): int
    {
        $minID = 0;
        foreach ($this->drivers as $id => $driver) {
            if ($driver->getDoneWeek() < $this->drivers[$minID]->getDoneWeek()) {
                $minID = $id;
            }
        }
        return $minID;
    }

    public function getMinHoursWeekID(): int
    {
        $minID = 0;
        foreach ($this->drivers as $id => $driver) {
            if ($driver->getWeekHoursAtWork() < $this->drivers[$minID]->getWeekHoursAtWork()) {
                $minID = $id;
            }
        }
        return $minID;
    }

    /**
     * @return array
     */
    public function getDrivers(): array
    {
        return $this->drivers;
    }

    /**
     * @return int
     */
    public function getStartDate(): int
    {
        return $this->startDate;
    }

    /**
     * @param int $startDate
     */
    public function setStartDate(int $startDate): void
    {
        $this->startDate = $startDate;
    }
}