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

        <div class="navbar-header pull-left">
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

        <div class="navbar-header pull-right usermenu-wrap">
            <a href="/login/index.php" class="login-btn btn btn-default btn-sm"><?php echo get_string('login', 'theme_totara110base') ?></a>
            <?php
                echo $OUTPUT->navbar_plugin_output();
                // Add profile menu (for logged in) or language menu (not logged in).
                $haslangmenu = (!isset($PAGE->layout_options['langmenu']) || $PAGE->layout_options['langmenu'] );
                echo ($haslangmenu && (!isloggedin() || isguestuser()) ? $OUTPUT->lang_menu() : '') . $OUTPUT->user_menu();
            ?>
        </div>

    </div>
</nav>

<div id="totara-header-bar" class="clearfix">
    <div class="sb-slide">
        <?php echo theme_totara110base_fetch_sidebar_toggle_button($PAGE->theme->settings); ?>
    </div>

    <div id="sidebar-block" class="sb-slidebar sb-<?php echo $slidebarside; ?> sb-style-push">
        <div class="social-links">
            <?php echo $socialicons ?>
        </div>
        <div class="totara-menu-sidebar">
            <?php if ($hastotaramenu) { echo $totaramenu; } ?>
        </div>
        <?php echo $OUTPUT->blocks('sidebar-block', 'sidebar-block'); ?>
    </div>

    <div class="sb-slide">
        <div id="totara-menu-header">
            <?php if ($hastotaramenu) { echo $totaramenu; } ?>
        </div>
        <div id="totara-header-bar-icons" class="social-links">
            <?php echo $socialicons ?>
        </div>
        <a href="/login/index.php" id="header-login-btn" class="login-btn btn btn-default btn-sm"><?php echo get_string('login', 'theme_totara110base') ?></a>
        <?php
            // Add profile menu (for logged in) or language menu (not logged in).
            $haslangmenu = (!isset($PAGE->layout_options['langmenu']) || $PAGE->layout_options['langmenu'] );
            echo ($haslangmenu && (!isloggedin() || isguestuser()) ? $OUTPUT->lang_menu() : '') . $OUTPUT->user_menu();
        ?>
    </div>
</div>

