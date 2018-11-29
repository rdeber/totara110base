<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2016 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright 2016 onwards Totara Learning Solutions LTD
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @package   theme_totara110base
 */

defined('MOODLE_INTERNAL') || die();

?>

<footer id="page-footer">
    <div class="footer-content container-fluid">
        <div class="row-fluid">
            <?php echo $OUTPUT->blocks('footer-one', 'col-sm-12 col-md-4'); ?>
            <?php echo $OUTPUT->blocks('footer-two', 'col-sm-12 col-md-4'); ?>
            <?php echo $OUTPUT->blocks('footer-three', 'col-sm-12 col-md-4'); ?>
        </div>
    </div>
</footer>

<footer id="page-footer2">
    <div class="footer-content page-footer-main-content container-fluid">
        <div class="row-fluid">
            <?php echo $OUTPUT->blocks('footer-four', 'col-xs-12'); ?>
	        <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
            <p class="helplink"><?php echo $OUTPUT->page_doc_link(); ?></p>
	        <div class="page-footer-loggedin-info">
	            <?php echo $OUTPUT->login_info(); ?>
	        </div>
	        <?php echo $OUTPUT->standard_footer_html(); ?>
        </div>
    </div>
</footer>
