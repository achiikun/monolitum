<?php

// locale: Catalan (ca_ES)
// author: CROWD Studio https://github.com/crowd-studio

use monolitum\legacy_moment\Moment;

return array(
    "months"        => explode('_', 'gener_febrer_març_abril_maig_juny_juliol_agost_setembre_octubre_novembre_desembre'),
    "monthsShort"   => explode('_', 'gen._febr._mar._abr._mai._jun._jul._ag._set._oct._nov._des.'),
    "weekdays"      => explode('_', 'dilluns_dimarts_dimecres_dijous_divendres_dissabte_diumenge'),
    "weekdaysShort" => explode('_', 'dl._dt._dc._dj._dv._ds._dg.'),
    "calendar"      => array(
        "sameDay"  => '[avui]',
        "nextDay"  => '[demà]',
        "lastDay"  => '[ahir]',
        "lastWeek" => '[el] l',
        "sameElse" => 'l',
        "withTime" => function (Moment $moment) { return '[a' . ($moment->getHour() != 1 ? ' les ' : ' l\'') . ']G.i [h]'; },
        "default"  => 'd/m/Y',
    ),
    "relativeTime"  => array(
        "future" => 'en %s',
        "past"   => 'fa %s',
        "s"      => 'uns segons',
        "ss"      => '%d segons',
        "m"      => 'un minut',
        "mm"     => '%d minuts',
        "h"      => 'una hora',
        "hh"     => '%d hores',
        "d"      => 'un dia',
        "dd"     => '%d dies',
        "M"      => 'un mes',
        "MM"     => '%d mesos',
        "y"      => 'un any',
        "yy"     => '%d anys',
    ),
    "ordinal"       => function ($number)
    {

        switch ($number) {
            case 1:
                $output = 'r';
                break;
            case 2:
                $output = 'n';
                break;
            case 3:
                $output = 'r';
                break;
            case 4:
                $output = 't';
                break;
            default:
                $output = 'è';
                break;
        }

        return $number . '[' . $output . ']';
    },
    "week"          => array(
        "dow" => 1, // Monday is the first day of the week.
        "doy" => 4  // The week that contains Jan 4th is the first week of the year.
    ),
    "customFormats" => array(
        "LT" => "G:i",               // 22:00
        "LTS" => "G:i:s",            // 22:00:00
        "L" => "d/m/Y",              // 12/06/2010
        "l" => "j/n/Y",              // 12/6/2010
        "LL" => "j [de] F [del] Y",  // 12 de Juny del 2010
        "ll" => "j M Y",             // 12 Jun 2010
        "LLL" => "j [de] F [del] Y [a les] G:i",        // 12 de Juny del 2010 a les 22:00
        "lll" => "j M Y G:i",        // 12 Jun 2010 22:00
        "LLLL" => "l, j [de] F [del] Y [a las] G:i",  // Dissabte, 12 de Juny del 2010 a les 22:00
        "llll" => "D, j M Y G:i",    // Sab, 12 Jun 2010 22:00
    ),
);
