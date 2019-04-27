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

namespace App\Domain\Groups\RemoveMember;

use App\Domain\AbstractPersister;

/**
 * Class Persister
 */
class Persister extends AbstractPersister
{
    /**
     * @param RemoveMemberInput $input
     */
    public function save(RemoveMemberInput $input)
    {
        $input->getGroup()->removeMember($input->getMember());
        $this->entityManager->flush();
    }
}
