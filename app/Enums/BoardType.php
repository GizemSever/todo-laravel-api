<?php
/**
 * @author Gizem Sever <gizemsever68@gmail.com>
 */

namespace App\Enums;

abstract class BoardType extends BaseEnum
{
    public const TODO = 'todo';
    public const ONGOING = 'ongoing';
    public const DONE = 'done';
}
