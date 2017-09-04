<?php

namespace Arkschools\DataInputSheets\Security;

use Arkschools\DataInputSheets\Sheet;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SheetAccessVoter extends Voter
{
    const ACCESS = 'data_input_sheet_access';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject    The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        if (self::ACCESS !== $attribute) {
            return false;
        }

        if (!$subject instanceof Sheet) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Sheet $subject */
        $users = $subject->getUsers();

        return empty($users) || in_array($token->getUsername(), $users);
    }
}
