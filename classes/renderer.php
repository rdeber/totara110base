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
 * @author    Joby Harding <joby.harding@totaralearning.com>
 * @package   theme_roots
 */

defined('MOODLE_INTERNAL' || die());

class theme_totara110base_renderer extends theme_roots_renderer {

}

include_once($CFG->dirroot . "/course/lib.php");

class theme_totara110base_core_renderer extends core_renderer {

    /**
     * Renders a pix_icon widget and returns the HTML to display it.
     *
     * @param pix_icon $icon
     * @return string HTML fragment
     */
    // protected function render_pix_icon(pix_icon $icon) {
    //     echo 'override render_pix_icon';
    //     echo ' |||| ';
    //     echo print_r($icon);
    //     echo ' |||| ';
    //     $flexicon = \core\output\flex_icon::create_from_pix_icon($icon);
    //     // echo print_r($flexicon);
    //     // TODO Put a check here for the setting and incorporate that into the
    //     // conditional logic.
    //     if (\core\output\flex_icon::exists($icon->component . '|icon')) {
    //         $flexicon = new \core\output\flex_icon($icon->component . '|icon', $customdata);
    //         // return $output->render($flexicon);
    //         return $this->render($flexicon);
    //     } else {
    //         // $flexicon = new \core\output\flex_icon('settings', $customdata);
    //         $flexicon = \core\output\flex_icon::create_from_pix_icon($icon);
    //         // return $output->render($flexicon);
    //         return $this->render($flexicon);
    //     }

    //     // if ($flexicon) {
    //     //     return $this->render($flexicon);
    //     // }

    //     $data = $icon->export_for_template($this);
    //     $template = $icon->get_template();

    //     return $this->render_from_template($template, $data);
    // }

    /**
     * Return HTML for a pix_icon.
     *
     * Theme developers: DO NOT OVERRIDE! Please override function
     * {@link core_renderer::render_pix_icon()} instead.
     *
     * @param string $pix short pix name
     * @param string $alt mandatory alt attribute
     * @param string $component standard compoennt name like 'moodle', 'mod_forum', etc.
     * @param array $attributes htm lattributes
     * @return string HTML fragment
     */
    // public function pix_icon($pix, $alt, $component='moodle', array $attributes = null) {
    //     // Check the mod's existence in the flex icon library.
    //     echo 'overriding pix_icon';
    //     echo ' $component is '.$component;
    //     $ismod = strpos($component, 'mod_');
    //     if ($ismod !== false) {
    //         echo 'mod_ is in the component string';
    //         echo '$component = '.$component;
    //         $exists = \core\output\flex_icon::exists($component . '|icon');
    //         echo '$exists = '.$exists;
    //     }
    //     // if "mod_" is in the component string
    //     // then check to see if it can be mapped to a flex icon
    //     // if not, then change it to the settings default.

    //     $icon = new pix_icon($pix, $alt, $component, $attributes);
    //     return $this->render($icon);
    //     // if (!!$forceflex) {
    //     //     $flexicon = \core\output\flex_icon::create_from_pix_icon($icon);
    //     //     return $this->render($flexicon);
    //     // } else {
    //     //     return $this->render($icon);
    //     // }
    // }

}
