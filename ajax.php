<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Ajax request
 * @package    block_tutor_dashboard
 * @copyright  2017 Hellen Cunha hcunha@plus-it.com.br
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('AJAX_SCRIPT')) {
    define('AJAX_SCRIPT', true);
}
require_once('../../../../../config.php');
require_once($CFG->dirroot . '/blocks/unifiedcloset/locallib.php');
require_once($CFG->dirroot . '/lib/filelib.php');

require_login();

$dirid = optional_param('dirid', 0, PARAM_RAW);
$username = optional_param('username', null, PARAM_RAW);

$return = $block_unifiedcloset_grid->display_atto($dirid,$username);

echo json_encode($return);
die;