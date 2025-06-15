<?php

namespace App\Enums;

enum ImageType : string
{
    case CommercialRegister = 'CommercialRegister';
    case HealthCertificate = 'HealthCertificate';
    case Profile = 'Profile';
    case Cover = 'Cover';
    case Details = 'Details';
}
