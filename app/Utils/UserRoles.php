<?php

namespace App\Utils;

enum UserRoles: string {

    case Admin = 'admin';

    case Doctor = 'doctor';

    case Client = 'client';

}