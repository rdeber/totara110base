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
 * @author    Joby Harding <joby.harding@totaralearning.com>
 */

defined('MOODLE_INTERNAL') || die();

global $OUTPUT;
?>
<nav role="navigation" class="navbar navbar-default navbar-site sb-slide">
    <div class="container-fluid header-wrap">

        <div class="navbar-header">
            <div id="logo">
                <a href="<?php echo $CFG->wwwroot;?>">
                    <?php if (isset($themefiles['logo'])) {
                        echo html_writer::empty_tag('img', array('alt' => get_string('logoalt', 'theme_totara110base'), 'src' => $themefiles['logo']));
                    } else { ?>
                        <img src="<?php echo $OUTPUT->pix_url('logo', 'theme'); ?>" alt="<?php echo get_string('logoalt', 'theme_totara110base')?>" />
                    <?php } ?>
                </a>
            </div>
        </div>

    </div>
</nav>