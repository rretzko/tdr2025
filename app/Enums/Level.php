<?php

namespace App\Enums;

enum Level: string
{
    case Elementary = 'elementary';
    case MiddleSchool = 'middle-school';
    case HighSchool = 'high-school';
    case College = 'college';
    case Professional = 'professional';
}
