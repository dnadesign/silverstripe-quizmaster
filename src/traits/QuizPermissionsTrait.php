<?php

namespace DNADesign\QuizMaster\Traits;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Folder;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\Security\Permission;

/**
 * A trait to provide access permission to all Quiz Models
 */
trait QuizPermissionsTrait
{
    /**
     * @param mixed $member
     * @param array $context
     * @return boolean
     */
    public function canCreate($member = null, $context = [])
    {
        return Permission::check('CMS_ACCESS_QUIZMASTER', 'any', $member);
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canDelete($member = null)
    {
        return Permission::check('CMS_ACCESS_QUIZMASTER', 'any', $member);
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canEdit($member = null)
    {
        return Permission::check('CMS_ACCESS_QUIZMASTER', 'any', $member);
    }

    /**
     * @param mixed $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return Permission::check('CMS_ACCESS_QUIZMASTER', 'any', $member);
    }
}