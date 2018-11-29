<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2015 onwards Totara Learning Solutions LTD
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
 * @copyright 2015 onwards Totara Learning Solutions LTD
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby.harding@totaralms.com>
 * @author    Petr Skoda <petr.skoda@totaralms.com>
 * @package   core
 */

// namespace theme\totara110base\output\flex_icon;

// use \pix_icon;
// use \flex_icon;

defined('MOODLE_INTERNAL') || die();

////require_once($CFG->dirroot. '/course/format/weeks/lib.php');
require_once($CFG->dirroot. '/lib/classes/output/flex_icon.php');
// require_once($CFG->dirroot. '/lib/classes/output/pix_icon.php');

/**
 * Flexible icon class. Provides a flexible framework for outputting icons via fonts.
 *
 * @copyright 2015 onwards Totara Learning Solutions LTD
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author    Joby Harding <joby.harding@totaralms.com>
 * @author    Petr Skoda <petr.skoda@totaralms.com>
 * @package   core
 */
class theme_totara110base_flex_icon extends flex_icon {

    /**
     * Create a flex icon from legacy pix_icon if possible.
     *
     * @param string|\moodle_url $pixurl
     * @param string|array $customdata list of custom classes added to flex icon
     * @return flex_icon|null returns null if flex matching flex icon cannot be found
     */
    public static function create_from_pix_url($pixurl, $customdata = null) {
        $pixurl = (string)$pixurl;

        if (strpos($pixurl, 'image=') !== false) {
            // Slasharguments disabled.
            $pixurl = urldecode($pixurl);
            if (!preg_match('|component=([0-9a-z_]+).*image=([0-9a-z_/]+)|', $pixurl, $matches)) {
                return null;
            }
            echo $flexidentifier;
            $flexidentifier = \core_component::normalize_componentname($matches[1]) . '|' . $matches[2];

        } else {
            if (!preg_match('|\.php/(_s/)?[a-z0-9_]+/([0-9a-z_]+)/-?[0-9]+/([0-9a-z_/]+)|', $pixurl, $matches)) {
                return null;
            }
            echo $flexidentifier;
            $flexidentifier = \core_component::normalize_componentname($matches[2]) . '|' . $matches[3];
        }

        if (!self::exists($flexidentifier)) {
            // return null;
            $flexidentifier = 'settings';
        }

        return new flex_icon($flexidentifier, $customdata);
    }

}