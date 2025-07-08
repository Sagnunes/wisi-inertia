<?php

namespace App\Enums;

enum ObjectType: int
{
    case forAUTHENTICATION = 1;
    case forOrders = 2;
    case forDigitalCollection = 3;
}
