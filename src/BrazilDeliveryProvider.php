<?php

/**
* @author Cristiano Soares
* @site comerciobr.com
* @email contato@comerciobr.com
*
* Base para cálculo carnaval e Corpus Christi
* http://www.inf.ufrgs.br/~cabral/Pascoa.html
*/

namespace johnykvsky\Utils;

/**
 * Calculate delivery time, only workdays
 */
class BrazilDeliveryProvider implements \johnykvsky\Utils\DeliveryProviderInterface
{
    /**
     * @var string $timezone Timezone name
     */
    public $timezone = 'America/Sao_Paulo';

    /**
     * @var array $holidays Holidays dates
     */
    public $holidays = [];

    /**
     * @var array $nonWorkingDays Non working days (weekends)
     */
    public $nonWorkingDays = array('0','6'); //no delivery on saturday 6, and sunday 0

    /**
     * @var string $region Region to deliver
     */
    public $region;

    /**
      * {@inheritdoc}
      */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
      * {@inheritdoc}
      */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
      * {@inheritdoc}
      */
    public function addHoliday($date)
    {
        $this->holidays[] = $date;
    }

    /**
      * {@inheritdoc}
      */
    public function getDateTimeZone()
    {
        return new \DateTimeZone($this->timezone);
    }

    /**
      * {@inheritdoc}
      */
    public function getNonWorkingWeekDays()
    {
        return $this->nonWorkingDays;
    }

    /**
      * {@inheritdoc}
      */
    public function setNonWorkingWeekDays($nonWorkingWeekDays)
    {
        $this->nonWorkingDays = $nonWorkingWeekDays;
    }

    /**
      * {@inheritdoc}
      */
    public function getHolidays($year)
    {
        $timezone = $this->getDateTimeZone();
        $date = \DateTime::createFromFormat('Y-m-d', $year.'-01-01', $timezone);
        $year = $date->format('Y');

        $easter = easter_date($year);

        $this->addHoliday($year . '-01-01'); // Ano Novo
        $this->addHoliday(date('Y-m-d', strtotime('-47 day', $easter))); // Carnaval
        $this->addHoliday(date('Y-m-d', strtotime('-2 day', $easter))); // Sexta-feira Santa
        $this->addHoliday(date('Y-m-d', $easter)); // Páscoa
        $this->addHoliday(date('Y-m-d', strtotime('+60 day', $easter))); // Corpus Christi
        $this->addHoliday($year . '-04-21'); // Tiradentes
        $this->addHoliday($year . '-05-01'); // Dia do Trabalhador
        $this->addHoliday($year . '-09-07'); // Independência
        $this->addHoliday($year . '-10-12'); // Dia das Crianças/Aparecida
        $this->addHoliday($year . '-11-02'); // Finados
        $this->addHoliday($year . '-11-15'); // Dia da bandeira
        $this->addHoliday($year . '-12-25'); // Natal

        $this->addRegionHolidays($year, $easter);

        return $this->holidays;
    }

    /**
      * {@inheritdoc}
      */
    public function addRegionHolidays($year, $easter)
    {
        return;
    }
}
