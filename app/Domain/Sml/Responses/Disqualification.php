<?php

namespace App\Domain\Sml\Responses;

use Illuminate\Support\Carbon;

class Disqualification
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
     * @return Disqualification
     */
    public function setCategory(string $category): Disqualification
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string $points
     * @return Disqualification
     */
    public function setPoints(string $points): Disqualification
    {
        $this->points = (int)$points;
        return $this;
    }

    /**
     * @param string $date
     * @return Disqualification
     */
    public function setDate(string $date): Disqualification
    {
        $this->date = Carbon::createFromFormat('d/m/Y', $date);
        return $this;
    }

    /**
     * @param string $date
     * @return Disqualification
     */
    public function setOffenseDate(string $date): Disqualification
    {
        $this->offenseDate = Carbon::createFromFormat('d/m/Y', $date);;
        return $this;
    }

    /**
     * @param string $date
     * @return Disqualification
     */
    public function setOffenceExpiryDate(string $date): Disqualification
    {
        $this->offenceExpiryDate = Carbon::createFromFormat('d/m/Y', $date);;
        return $this;
    }

    /**
     * @param string $date
     * @return Disqualification
     */
    public function setOffenceRemovalDate(string $date): Disqualification
    {
        $this->offenceRemovalDate = Carbon::createFromFormat('d/m/Y', $date);;
        return $this;
    }

    /**
     * @param string $offenceDescription
     * @return Disqualification
     */
    public function setOffenceDescription(string $offenceDescription): Disqualification
    {
        $this->offenceDescription = $offenceDescription;
        return $this;
    }

    /**
     * @param string $offencePoints
     * @return Disqualification
     */
    public function setOffencePoints(string $offencePoints): Disqualification
    {
        $this->offencePoints = (int)$offencePoints;
        return $this;
    }

    /**
     * @param string $offenceFine
     * @return Disqualification
     */
    public function setOffenceFine(string $offenceFine): Disqualification
    {
        $this->offenceFine = (float)$offenceFine;
        return $this;
    }

    /**
     * @param string $penaltyOffice
     * @return Disqualification
     */
    public function setPenaltyOffice(string $penaltyOffice): Disqualification
    {
        $this->penaltyOffice = $penaltyOffice;
        return $this;
    }
}
