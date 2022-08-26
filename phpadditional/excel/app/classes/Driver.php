<?php


class Driver
{
    private string $name;
    private int $hours;
    private int $hoursToday;
    private int $hoursWeek;
    private int $prod; //productivity
    private int $doneWeek;
    private int $doneToday;

    /**
     * Driver constructor.
     * @param string $name
     * @param int $hours
     * @param int $prod
     */
    public function __construct(string $name, int $hours, int $prod)
    {
        $this->name = $name;
        $this->hours = $hours;
        $this->prod = $prod;
        $this->doneWeek = 0;
        $this->hoursToday = 0;
        $this->doneToday = 0;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getHours(): int
    {
        return $this->hours;
    }

    /**
     * @param int $hoursToday
     */
    public function setHoursToday(int $hoursToday): void
    {
        $this->hoursToday = $hoursToday;
    }

    /**
     * @return int
     */
    public function getHoursToday(): int
    {
        return $this->hoursToday;
    }

    /**
     * @return int
     */
    public function getDone(): int
    {
        return $this->done;
    }

    /**
     * @param int $done
     */
    public function addDoneWeek(int $done): void
    {
        $this->doneWeek += $done;
    }

    /**
     * @param int $doneToday
     */
    public function setDoneToday(int $doneToday): void
    {
        $this->doneToday += $doneToday;
        $this->addDoneWeek($doneToday);
    }

    public function resetDoneToday(): void
    {
        $this->doneToday = 0;
    }
    /**
     * @return int
     */
    public function getDoneToday(): int
    {
        return $this->doneToday;
    }

    /**
     * @return int
     */
    public function getDoneWeek(): int
    {
        return $this->doneWeek;
    }

    /**
     * @return int
     */
    public function getProd(): int
    {
        return $this->prod;
    }

    public function getWeekHoursAtWork(){
        return $this->doneWeek / $this->prod;
    }

}