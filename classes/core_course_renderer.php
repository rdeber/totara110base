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

require_once("{$CFG->dirroot}/course/renderer.php");

class theme_totara110base_core_course_renderer extends core_course_renderer {

    protected $gridstyle = false;

    /**
     * Returns HTML to print tree with course categories and courses for the frontpage
     * Always set gridstyle to false for combo list.
     *
     * @return string
     */
    public function frontpage_combo_list() {
        // Reset the grid style flag to false for combo list.
        $this->gridstyle = false;
        return parent::frontpage_combo_list();
    }

    /**
     * Returns HTML to print tree of course categories (with number of courses) for the frontpage
     * Always set gridstyle to false for combo list.
     *
     * @return string
     */
    public function frontpage_categories_list() {
        // Reset the grid style flag to false for categories list.
        $this->gridstyle = false;
        return parent::frontpage_categories_list();
    }

    /**
     * Returns HTML to print list of available courses for the frontpage
     *
     * @return string
     */
    public function frontpage_available_courses() {
        // Check for enhanced course widgets setting, and set gridstyle flag accordingly.
        global $PAGE;
        $enhanced = false;
        if (isset($PAGE->theme->settings->enhancedcoursewidgets)) {
           $enhanced = $PAGE->theme->settings->enhancedcoursewidgets;
        }
        if ($enhanced == true) {
            $this->gridstyle = true;
        } else {
            $this->gridstyle = false;
        }
        return parent::frontpage_available_courses();
    }

    /**
     * Returns HTML to print list of courses user is enrolled to for the frontpage
     *
     * Also lists remote courses or remote hosts if MNET authorisation is used
     *
     * @return string
     */
    public function frontpage_my_courses() {
        // Check for enhanced course widgets setting, and set gridstyle flag accordingly.
        global $PAGE;
        $enhanced = false;
        if (isset($PAGE->theme->settings->enhancedcoursewidgets)) {
           $enhanced = $PAGE->theme->settings->enhancedcoursewidgets;
        }
        if ($enhanced == true) {
            $this->gridstyle = true;
        } else {
            $this->gridstyle = false;
        }
        return parent::frontpage_my_courses();
    }

    /**
     * Displays one course in the list of courses.
     *
     * This is an internal function, to display an information about just one course
     * please use {@link core_course_renderer::course_info_box()}
     *
     * @param coursecat_helper $chelper various display options
     * @param course_in_list|stdClass $course
     * @param string $additionalclasses additional classes to add to the main <div> tag (usually
     *    depend on the course position in list - first/last/even/odd)
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $PAGE;
        if ($this->gridstyle === true) {
            global $CFG;
            if (!isset($this->strings->summary)) {
                $this->strings->summary = get_string('summary');
            }
            if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
                return '';
            }
            if ($course instanceof stdClass) {
                require_once($CFG->libdir. '/coursecatlib.php');
                $course = new course_in_list($course);
            }

            $courselink = new moodle_url('/course/view.php', array('id' => $course->id));

            $content = '';

            $classes = trim('coursebox gridcoursebox clearfix '. $additionalclasses);
            $nametag = 'h3';

            // .coursebox
            $content .= html_writer::start_tag('div', array(
                'class' => $classes,
                'data-courseid' => $course->id,
                'data-type' => self::COURSECAT_TYPE_COURSE,
            ));

            $content .= html_writer::start_tag('div', array('class' => 'info'));

            // display course overview files
            $contentimage = '';
            foreach ($course->get_course_overviewfiles() as $file) {
                $isimage = $file->is_valid_image();
                $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                        '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                        $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
                if ($isimage) {
                    $contentimage .= html_writer::tag('div',
                            html_writer::empty_tag('img', array('src' => $url)),
                            array('class' => 'gridcourseimage'));
                    break;
                }
            }
            $content .= $coursenamelink = html_writer::link($courselink, $contentimage);

            // course name
            require_once($CFG->dirroot . "/totara/core/utils.php");
            require_once($CFG->dirroot . "/totara/coursecatalog/lib.php");
            $coursename = $chelper->get_course_formatted_name($course);
            $dimmed = totara_get_style_visibility($course);
            $coursenamelink = html_writer::link(
                $courselink,
                $coursename,
                array('class' => $dimmed, 'style' => 'background: transparent;')
            );
            $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));

            // print enrolmenticons
            if ($icons = enrol_get_course_info_icons($course)) {
                $content .= html_writer::start_tag('div', array('class' => 'enrolmenticons'));
                foreach ($icons as $pix_icon) {
                    $content .= $this->render($pix_icon);
                }
                $content .= html_writer::end_tag('div'); // .enrolmenticons
            }

            $content .= html_writer::end_tag('div'); // .info

            $content .= html_writer::start_tag('div', array('class' => 'content'));

            // Print summary and handle word limit setting.
            $wordlimit = 0;
            if (isset($PAGE->theme->settings->enhancedcoursewidgetsummarylimit) && is_int(intval($PAGE->theme->settings->enhancedcoursewidgetsummarylimit))) {
                $wordlimit = intval($PAGE->theme->settings->enhancedcoursewidgetsummarylimit);
            }
            $fullsummary = format_string($course->summary);
            $summarywords = explode(" ", $fullsummary);
            if (count($summarywords) > $wordlimit && $wordlimit > 0) {
                $summary = "";
                for($i=0; $i<$wordlimit; $i++) {
                    $summary .= $summarywords[$i]." ";
                }
                $summary .= get_string('enhancedcoursewidgetsummarytruncated', 'theme_totara110base');
                $content .= html_writer::tag('div', $summary, array('class' => 'gridcoursesummary', 'title' => $fullsummary));
            } else {
                $content .= html_writer::tag('div', $fullsummary, array('class' => 'gridcoursesummary'));
            }

            // display course contacts. See course_in_list::get_course_contacts()
            if ($course->has_course_contacts()) {
                $content .= html_writer::start_tag('ul', array('class' => 'gridcoursecontacts'));
                foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                    $name = $coursecontact['rolename'].': '.
                            html_writer::link(new moodle_url('/user/view.php',
                                    array('id' => $userid, 'course' => SITEID)),
                                $coursecontact['username']);
                    $content .= html_writer::tag('li', $name);
                }
                $content .= html_writer::end_tag('ul'); // .gridcoursecontacts
            }

            $content .= html_writer::end_tag('div'); // .content

            // Add view course button/link
            $content .= html_writer::link($courselink, get_string('enhancedcoursewidgetbuttontext', 'theme_totara110base'), array('class' => 'btn btn-primary gridcoursebutton'));

            $content .= html_writer::end_tag('div'); // .coursebox
            return $content;
        } else {
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses);
        }
    }

    /**
     * Return an activity icon markup.
     *
     * @param core_renderer $output Output render to use, or null for default (global)
     * @param string $classes CSS classes
     * @return string html
     */
    public function theme_totara110base_render_icon($output = null, $classes = '', $mod) {
        global $OUTPUT, $CFG, $COURSE, $USR, $PAGE;
        require_once($CFG->dirroot.'/lib/modinfolib.php');
        if (!$output) {
            $output = $OUTPUT;
        }

        $iconpixurl = $mod->get_icon_url($output);
        $customdata = array();
        if ($classes) {
            $customdata['classes'] = $classes;
        }

        $flexicon = \core\output\flex_icon::create_from_pix_url($iconpixurl, $customdata);
        if ($flexicon) {
            return $output->render($flexicon);
        }

        if (\core\output\flex_icon::exists($mod->modname . '|icon')) {
            $flexicon = new \core\output\flex_icon($mod->modname . '|icon', $customdata);
            return $output->render($flexicon);
        } else {
            // If setting to override img icons, then return the flexicon
            // for the default 'settings' mapping.
            $forceflexicons = isset($PAGE->theme->settings->replaceimgicons) ?
                $PAGE->theme->settings->replaceimgicons : 0;
            if (!!$forceflexicons) {
                $flexicon = new \core\output\flex_icon('settings', $customdata);
                return $output->render($flexicon);
            }
        }

        $attributes = array(
            'class' => $classes,
            'role' => 'presentation',
            'alt' => '',
        );
        return html_writer::img($iconpixurl, '', $attributes);
    }

    /**
     * Renders html to display a name with the link to the course module on a course page
     *
     * If module is unavailable for user but still needs to be displayed
     * in the list, just the name is returned without a link
     *
     * Note, that for course modules that never have separate pages (i.e. labels)
     * this function return an empty string
     *
     * @param cm_info $mod
     * @param array $displayoptions
     * @return string
     */
    public function course_section_cm_name(cm_info $mod, $displayoptions = array()) {
        global $CFG, $PAGE, $OUTPUT;
        $output = '';
        if (!$mod->uservisible && empty($mod->availableinfo)) {
            // nothing to be displayed to the user
            return $output;
        }
        $url = $mod->url;
        if (!$url) {
            return $output;
        }

        //Accessibility: for files get description via icon, this is very ugly hack!
        $instancename = $mod->get_formatted_name();
        $altname = $mod->modfullname;
        // Avoid unnecessary duplication: if e.g. a forum name already
        // includes the word forum (or Forum, etc) then it is unhelpful
        // to include that in the accessible description that is added.
        if (false !== strpos(core_text::strtolower($instancename),
                core_text::strtolower($altname))) {
            $altname = '';
        }
        // File type after name, for alphabetic lists (screen reader).
        if ($altname) {
            $altname = get_accesshide(' '.$altname);
        }

        // For items which are hidden but available to current user
        // ($mod->uservisible), we show those as dimmed only if the user has
        // viewhiddenactivities, so that teachers see 'items which might not
        // be available to some students' dimmed but students do not see 'item
        // which is actually available to current student' dimmed.
        $linkclasses = '';
        $accesstext = '';
        $textclasses = '';
        if ($mod->uservisible) {
            $conditionalhidden = $this->is_cm_conditionally_hidden($mod);
            $accessiblebutdim = (!$mod->visible || $conditionalhidden) &&
                has_capability('moodle/course:viewhiddenactivities', $mod->context);
            if ($accessiblebutdim) {
                $linkclasses .= ' dimmed';
                $textclasses .= ' dimmed_text';
                if ($conditionalhidden) {
                    $linkclasses .= ' conditionalhidden';
                    $textclasses .= ' conditionalhidden';
                }
                // Show accessibility note only if user can access the module himself.
                $accesstext = get_accesshide(get_string('hiddenfromstudents').':'. $mod->modfullname);
            }
        } else {
            $linkclasses .= ' dimmed';
            $textclasses .= ' dimmed_text';
        }

        // Get on-click attribute value if specified and decode the onclick - it
        // has already been encoded for display (puke).
        $onclick = htmlspecialchars_decode($mod->onclick, ENT_QUOTES);

        $groupinglabel = $mod->get_grouping_label($textclasses);

        // Totara: Display link itself with flex icon.
        // If theme setting to force flex icons for img icons,
        // then use custom totara110base theme icon renderer.
        $forceflexicons = isset($PAGE->theme->settings->replaceimgicons) ?
            $PAGE->theme->settings->replaceimgicons : 0;
        if (!$forceflexicons) {
            $activitylink  = $mod->render_icon($OUTPUT, 'activityicon');
        } else {
            $classes = 'activityicon '.'mod_'.$mod->modname;
            $activitylink = $this->theme_totara110base_render_icon($OUTPUT, $classes, $mod);
        }
        $activitylink .= $accesstext;
        $activitylink .= html_writer::tag('span', $instancename . $altname, array('class' => 'instancename testing', 'data-movetext' => 'true'));

        if ($mod->uservisible) {
            $output .= html_writer::link($url, $activitylink, array('class' => $linkclasses, 'onclick' => $onclick)) .
                    $groupinglabel;
        } else {
            // We may be displaying this just in order to show information
            // about visibility, without the actual link ($mod->uservisible)
            $output .= html_writer::tag('div', $activitylink, array('class' => $textclasses)) .
                    $groupinglabel;
        }
        return $output;
    }


    /**
     * Return the HTML for the specified module adding any required classes
     *
     * @param object $module An object containing the title, and link. An
     * icon, and help text may optionally be specified. If the module
     * contains subtypes in the types option, then these will also be
     * displayed.
     * @param array $classes Additional classes to add to the encompassing
     * div element
     * @return string The composed HTML for the module
     */
    protected function course_modchooser_module($module, $classes = array('option')) {
        global $OUTPUT, $PAGE;
        $forceflexicons = isset($PAGE->theme->settings->replaceimgicons) ? $PAGE->theme->settings->replaceimgicons : 0;
        $flexiconexists = \core\output\flex_icon::exists('mod_'.$module->name.'|icon');
        // If there is no flex icon, and the item in question isn't the
        // activities or resources header, then force the generic flex icon
        // instead of using the module icon.
        if (!!$forceflexicons && !$flexiconexists && $module->name !== 'activities' && $module->name !== 'resources') {
            // Build customdata
            $customdata = [];
            $customdata['alt'] = $module->name;
            $customdata['title'] = $module->title;
            $customdata['classes'] = 'smallicon '.'mod_'.$module->name;
            // Generate icon and assign it to module object for later use.
            $flexicon = new \core\output\flex_icon('settings', $customdata);
            $module->icon = $OUTPUT->render($flexicon);
        }

        $output = '';
        $output .= html_writer::start_tag('div', array('class' => implode(' ', $classes)));
        if (!isset($module->types)) {
            $output .= html_writer::start_tag('label', array('for' => 'module_' . $module->name));
            $output .= html_writer::tag('input', '', array('type' => 'radio',
                    'name' => 'jumplink', 'id' => 'module_' . $module->name, 'value' => $module->link));
        }

        $output .= html_writer::start_tag('span', array('class' => 'modicon'));
        if (isset($module->icon)) {
            // Add an icon if we have one
            $output .= $module->icon;
        }
        $output .= html_writer::end_tag('span');

        $output .= html_writer::tag('span', $module->title, array('class' => 'typename'));
        if (!isset($module->help)) {
            // Add help if found
            $module->help = get_string('nohelpforactivityorresource', 'moodle');
        }

        // Format the help text using markdown with the following options
        $options = new stdClass();
        $options->trusted = false;
        $options->noclean = false;
        $options->smiley = false;
        $options->filter = false;
        $options->para = true;
        $options->newlines = false;
        $options->overflowdiv = false;
        $module->help = format_text($module->help, FORMAT_MARKDOWN, $options);
        $output .= html_writer::tag('span', $module->help, array('class' => 'typesummary'));
        if (!isset($module->types)) {
            $output .= html_writer::end_tag('label');
        }
        $output .= html_writer::end_tag('div');

        return $output;
    }

}
