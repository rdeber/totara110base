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
 * Admin setttings objects for component 'theme_totara110base'
 *
 * @package   theme-totara110base
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class totara110base_admin_setting_tabs extends admin_setting {

    protected $tabs     = array(0 => array());
    protected $selected;
    protected $section;
    protected $reload;

    /**
     * Config fileupload constructor
     *
     * @param string  $name    Unique ascii name, either 'mysetting' for settings that in
     *                         config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string  $section Section name
     * @param boolean $reload  Whether to reload
     */
    public function __construct($name, $section, $reload) {
        parent::__construct($name, '', '', '');

        global $PAGE;
        global $CFG;

        if (!$PAGE->requires->is_head_done()) {
            if (file_exists($CFG->dirroot.'/theme/totara110base/javascript/settings.js')) {
                $PAGE->requires->jquery();
                $PAGE->requires->js('/theme/totara110base/javascript/settings.js');
            }
        }

        $this->section = $section;
        $this->reload  = $reload;
        $this->component  = $this->plugin;
        $this->theme      = substr($this->component, 6);

        // Check for direct links.
        $this->selected = optional_param($this->get_full_name(), 0, PARAM_INT);

        if ($this->reload) {
            $newtab = optional_param($this->get_full_name() .'_new', -1, PARAM_INT);

            if ($newtab != -1) {
                $this->selected = $newtab;
            }
        }

    }

    /**
     * Return the currently selected tab.
     *
     * @return int The id of the currently selected tab.
     */
    public function get_setting() {
        return $this->selected;
    }

    /**
     * Write settings.
     *
     * In practice this actually runs the reset, import or export sub actions.
     *
     * @param array $data The submitted data to act upon.
     * @return string Always returns an empty string
     */
    public function write_setting($data) {
        $result = '';

        if (isset($data['action'])) {

            if ($data['action'] == 1) {
                $result = $this->reset();

            } else if ($data['action'] == 2) {
                $result = $this->import($data['picker']);

            } else if ($data['action'] == 3) {
                $result = $this->export();
            }
        }
        return $result;
    }

    /**
     * Add a tab to the tab row
     *
     * For now we only implement a single row.  Multiple rows could be added as an extension
     * later.
     *
     * @param int    $id   The tab id
     * @param string $name The tab name
     * @uses $CFG
     */
    public function addtab($id, $name) {
        global $CFG;

        $url = $CFG->wwwroot .'/admin/settings.php?section='. $this->section .'&amp;'
             .$this->get_full_name().'='.$id.'" class="totara110base-admin-tab';

        $tab = new tabobject($id, $url, $name);

        $this->tabs[0][] = $tab;
    }

    /**
     * Returns an HTML string
     *
     * @param mixed  $data  Array or string depending on setting
     * @param string $query Query
     * @return string Returns an HTML string
     */
    public function output_html($data, $query='') {

        global $CFG, $PAGE;

        $this->component = $this->plugin;
        $this->theme     = substr($this->component, 6);

        $output  = print_tabs($this->tabs, $this->selected, null, null, true);

        $properties = array(
                'type'  => 'hidden',
                'name'  => $this->get_full_name(),
                'value' => $this->get_setting()
        );

        $output .= html_writer::empty_tag('input', $properties);

        $properties['id']   = $this->get_id();
        $properties['name'] = $this->get_full_name() .'_new';

        $output .= html_writer::empty_tag('input', $properties);

        return $output;
    }
}
