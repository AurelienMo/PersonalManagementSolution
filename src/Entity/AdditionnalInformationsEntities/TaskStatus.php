<?php

declare(strict_types=1);

/*
 * This file is part of PersonalManagementSolution
 *
 * (c) Aurelien Morvan <morvan.aurelien@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity\AdditionnalInformationsEntities;

/**
 * Class TaskStatus
 */
class TaskStatus
{
    const TODO = 'todo';
    const IN_PROGRESS = 'in_progress';
    const TO_BE_VALIDATED = 'to_be_validated';
    const DONE = 'done';
}
