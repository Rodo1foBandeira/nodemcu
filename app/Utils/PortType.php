<?php
/**
 * Created by PhpStorm.
 * User: Rodolfo
 * Date: 19/04/2019
 * Time: 13:20
 */

namespace App;


class PortType
{
    const DIGITAL_OUTPUT = 'DIGITAL_OUTPUT';
    const DIGITAL_INPUT = 'DIGITAL_INPUT';
    const ANALOG_INPUT = 'ANALOG_INPUT';
    const ANALOG_OUTPUT = 'ANALOG_OUTPUT';
    const PWM_OUTPUT = 'PWM_OUTPUT';
    const INFRARED_OUTPUT = 'INFRARED_OUTPUT';
    const INFRARED_INPUT = 'INFRARED_INPUT';

    public static function types()
    {
        return ['DIGITAL_OUTPUT' => 'DIGITAL_OUTPUT','DIGITAL_INPUT'=>'DIGITAL_INPUT','ANALOG_INPUT'=>'ANALOG_INPUT','ANALOG_OUTPUT'=>'ANALOG_OUTPUT', 'PWM_OUTPUT'=>'PWM_OUTPUT', 'INFRARED_OUTPUT'=>'INFRARED_OUTPUT', 'INFRARED_INPUT'=>'INFRARED_INPUT'];
    }
}