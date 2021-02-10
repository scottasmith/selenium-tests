<?php

namespace App\Domain\Sml\Responses;

use Carbon\Carbon;

class Penalty
{
    private string $category;

    private int $points;

    private Carbon $date;

    private Carbon $offenseDate;

    private Carbon $offenceExpiryDate;

    private Carbon $offenceRemovalDate;

    private string $offenceDescription;

    private int $offencePoints;

    private float $offenceFine;

    private string $penaltyOffice;

    /**
     * @param string $category
     * @return Penalty
     */
    public function setCategory(string $category): Penalty
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string $points
     * @return Penalty
     */
    public function setPoints(string $points): Penalty
    {
        $this->points = (int) $points;
        return $this;
    }

    /**
     * @param string $date
     * @return Penalty
     */
    public function setDate(string $date): Penalty
    {
        $this->date = Carbon::createFromFormat('d/m/Y', $date);
        return $this;
    }

    /**
     * @param string $date
     * @return Penalty
     */
    public function setOffenseDate(string $date): Penalty
    {
        $this->offenseDate = Carbon::createFromFormat('d/m/Y', $date);;
        return $this;
    }

    /**
     * @param string $date
     * @return Penalty
     */
    public function setOffenceExpiryDate(string $date): Penalty
    {
        $this->offenceExpiryDate = Carbon::createFromFormat('d/m/Y', $date);;
        return $this;
    }

    /**
     * @param string $date
     * @return Penalty
     */
    public function setOffenceRemovalDate(string $date): Penalty
    {
        $this->offenceRemovalDate = Carbon::createFromFormat('d/m/Y', $date);;
        return $this;
    }

    /**
     * @param string $offenceDescription
     * @return Penalty
     */
    public function setOffenceDescription(string $offenceDescription): Penalty
    {
        $this->offenceDescription = $offenceDescription;
        return $this;
    }

    /**
     * @param string $offencePoints
     * @return Penalty
     */
    public function setOffencePoints(string $offencePoints): Penalty
    {
        $this->offencePoints = (int) $offencePoints;
        return $this;
    }

    /**
     * @param string $offenceFine
     * @return Penalty
     */
    public function setOffenceFine(string $offenceFine): Penalty
    {
        $this->offenceFine = (float) $offenceFine;
        return $this;
    }

    /**
     * @param string $penaltyOffice
     * @return Penalty
     */
    public function setPenaltyOffice(string $penaltyOffice): Penalty
    {
        $this->penaltyOffice = $penaltyOffice;
        return $this;
    }
}
