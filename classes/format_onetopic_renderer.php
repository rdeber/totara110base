<?php
//
// You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file contains main class for the course format Onetopic
 *
 * @since     2.0
 * @package   theme_totara110base
 * @author    Amy Groshek <amy@remote-learner.net>
 * @copyright 2017 Remote-Learner.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot. '/course/format/weeks/lib.php');
require_once($CFG->dirroot. '/course/format/onetopic/lib.php');

class theme_totara110base_format_onetopic_renderer extends format_onetopic_renderer {


    protected $_format_data;
    protected $_course;

    /**
     * AMT-136: Return whether or not to replace topics with weeks.
     * @return boolean Boolean to govern renderer overrides.
     */
    protected function get_totara110base_onetopic_weeks() {
        $useonetopicweeks = get_config('theme_totara110base', 'onetopicweeks') ?
            get_config('theme_totara110base', 'onetopicweeks') : false;
        return $useonetopicweeks;
    }

    /**
     * AMT-136: Return the start and end date of the passed section.
     * (Repurposed from the weeks course format.)
     *
     * @param int|stdClass|section_info $section section to get the dates for
     * @return stdClass property start for startdate, property end for enddate
     */
    public function get_section_dates($course, $section) {
        if (is_object($section)) {
            $sectionnum = $section->section;
        } else {
            $sectionnum = $section;
        }
        $oneweekseconds = 604800;
        // Hack alert. We add 2 hours to avoid possible DST problems. (e.g. we go into daylight
        // savings and the date changes.
        $startdate = $course->startdate + 7200;

        $dates = new stdClass();
        $dates->start = $startdate + ($oneweekseconds * ($sectionnum - 1));
        $dates->end = $dates->start + $oneweekseconds;

        return $dates;
    }

    /**
     * AMT-136: Returns the default section name for the weekly course format.
     *
     * If the section number is 0, it will use the string with key = section0name from
     * the weeks course format's lang file. Otherwise, the default format of
     * "[start date] - [end date]" will be returned.
     *
     * @param stdClass $section Section object from database or just field course_sections section
     * @return string The default value for the section name.
     */
    public function get_default_section_name($course, $section) {
        $onetopicweeks = $this->get_totara110base_onetopic_weeks();
        if (!!$onetopicweeks) {
            if ($section->section == 0) {
                // Return the general section.
                return get_string('section0name', 'format_weeks');
            } else {
                // Show onetopic weeks default section name.
                // This code comes from the weeks course layout.
                $dates = $this->get_section_dates($course, $section);

                // We subtract 24 hours for display purposes.
                $dates->end = ($dates->end - 86400);

                $dateformat = get_string('strftimedateshort');
                $weekday = userdate($dates->start, $dateformat);
                $endweekday = userdate($dates->end, $dateformat);
                return $weekday.' - '.$endweekday;
            }
        } else {

            if ((string)$section->name !== '') {
                return format_string($section->name, true,
                        array('context' => context_course::instance($course->id)));
            } else {
                // Show the regular section name shown by onetopic format.
                return get_string('sectionname', 'format_onetopic') . ' ' . $section->section;
            }
        }
    }

    /**
     * Generate next/previous section links for navigation
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param int $sectionno The section number in the coruse which is being dsiplayed
     * @return array associative array with previous and next section link
     */
    protected function get_nav_links($course, $sections, $sectionno) {
        // FIXME: This is really evil and should by using the navigation API.
        $course = course_get_format($course)->get_course();
        $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
            or !$course->hiddensections;

        $links = array('previous' => '', 'next' => '');
        $back = $sectionno - 1;

        while ((($back > 0 && $course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE) || ($back >= 0 && $course->realcoursedisplay != COURSE_DISPLAY_MULTIPAGE)) &&
                empty($links['previous'])) {
            if ($canviewhidden || $sections[$back]->uservisible) {
                $params = array();
                if (!$sections[$back]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $previouslink = html_writer::tag('span', $this->output->larrow(), array('class' => 'larrow'));
                $previouslink .= $this->get_default_section_name($course, $sections[$back]); // AMT-136
                $links['previous'] = html_writer::link(course_get_url($course, $back), $previouslink, $params);
            }
            $back--;
        }

        $forward = $sectionno + 1;
        if (!isset($course->numsections)) {
            $course->numsections = $this->gen_numsections();
        }
        while ($forward <= $course->numsections and empty($links['next'])) {
            if ($canviewhidden || $sections[$forward]->uservisible) {
                $params = array();
                if (!$sections[$forward]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $nextlink = $this->get_default_section_name($course, $sections[$forward]); // AMT-136
                $nextlink .= html_writer::tag('span', $this->output->rarrow(), array('class' => 'rarrow'));
                $links['next'] = html_writer::link(course_get_url($course, $forward), $nextlink, $params);
            }
            $forward++;
        }

        return $links;
    }

    /**
     * Output the html for a single section page .
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param array $mods used for print_section()
     * @param array $modnames used for print_section()
     * @param array $modnamesused used for print_section()
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE, $OUTPUT;

        $real_course_display = $course->realcoursedisplay;
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();
        $course->realcoursedisplay = $real_course_display;
        $sections = $modinfo->get_section_info_all();
        $onetopicweeks = $this->get_totara110base_onetopic_weeks(); // AMT-136

        // Can we view the section in question?
        $context = context_course::instance($course->id);
        $canviewhidden = has_capability('moodle/course:viewhiddensections', $context);

        if (!isset($sections[$displaysection])) {
            // This section doesn't exist
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);

        $format_data = new stdClass();
        $format_data->mods = $mods;
        $format_data->modinfo = $modinfo;
        $this->_course = $course;
        $this->_format_data = $format_data;

        // General section if non-empty and course_display is multiple.
        if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE) {
            $thissection = $sections[0];
            if ((($thissection->visible && $thissection->available) || $canviewhidden) && ($thissection->summary || $thissection->sequence || $PAGE->user_is_editing())) {
                echo $this->start_section_list();
                echo $this->section_header($thissection, $course, true);

                if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_NOT) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                }
                else if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_LIST) {
                    echo $this->custom_course_section_cm_list($course, $thissection, $displaysection);
                }

                echo $this->courserenderer->course_section_add_cm_control($course, 0, $displaysection);

                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section onetopic'));

        //Move controls
        $can_move = false;
        if ($PAGE->user_is_editing() && has_capability('moodle/course:movesections', $context) && $displaysection > 0) {
            $can_move = true;
        }
        $move_list_html = '';
        $count_move_sections = 0;

        //Init custom tabs
        $section = 0;

        $sectionmenu = array();
        $tabs = array();
        $inactive_tabs = array();

        $default_topic = -1;
        if (!isset($course->numsections)) {
            $course->numsections = $this->gen_numsections();
        }

        while ($section <= $course->numsections) {

            if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE && $section == 0) {
                $section++;
                continue;
            }

            $thissection = $sections[$section];

            $showsection = true;
            if (!$thissection->visible || !$thissection->available) {
                $showsection = false;
            }
            else if ($section == 0 && !($thissection->summary || $thissection->sequence || $PAGE->user_is_editing())){
                $showsection = false;
            }

            if (!$showsection) {
                $showsection = $canviewhidden || !$course->hiddensections;
            }

            if (isset($displaysection)) {
                if ($showsection) {

                    if ($default_topic < 0) {
                        $default_topic = $section;

                        if ($displaysection == 0) {
                            $displaysection = $default_topic;
                        }
                    }

                    $format_options = course_get_format($course)->get_format_options($thissection);

                    if (!!$onetopicweeks) {
                        // AMT-136: Reroute to date-based section name fetching.
                        $sectionname = $this->get_default_section_name($course, $thissection);
                    } else {
                        // Default onetopic behavior.
                        // Allows tabs above level 0 to have their own non-date name.
                        $sectionname = get_section_name($course, $thissection);
                    }

                    if ($displaysection != $section) {
                        $sectionmenu[$section] = $sectionname;
                    }

                    $custom_styles = '';
                    $level = 0;
                    if (is_array($format_options)) {

                        if (!empty($format_options['fontcolor'])) {
                            $custom_styles .= 'color: ' . $format_options['fontcolor'] . ';';
                        }

                        if (!empty($format_options['bgcolor'])) {
                            $custom_styles .= 'background-color: ' . $format_options['bgcolor'] . ';';
                        }

                        if (!empty($format_options['cssstyles'])) {
                            $custom_styles .= $format_options['cssstyles'] . ';';
                        }

                        if (isset($format_options['level'])) {
                            $level = $format_options['level'];
                        }
                    }

                    if ($section == 0) {
                        $url = new moodle_url('/course/view.php', array('id' => $course->id, 'section' => 0));
                    } else {
                        $url = course_get_url($course, $section);
                    }

                    $special_style = 'tab_position_' . $section . ' tab_level_' . $level;
                    if ($course->marker == $section) {
                        $special_style = ' marker ' . ' tab_level_' . $level;
                    }

                    if (!$thissection->visible || !$thissection->available) {
                        $special_style .= ' dimmed ';

                        if (!$canviewhidden) {
                            $inactive_tabs[] = "tab_topic_" . $section;
                        }
                    }
                    $sectionname = htmlspecialchars_decode($sectionname);
                    $new_tab = new tabobject("tab_topic_" . $section, $url,
                    '<div style="' . $custom_styles . '" class="tab_content ' . $special_style . '">' . s($sectionname) . "</div>", s($sectionname));

                    if (is_array($format_options) && isset($format_options['level'])) {

                        if($format_options['level'] == 0 || count($tabs) == 0) { // If level = 0 or no tabs.
                            $tabs[] = $new_tab;
                            $new_tab->level = 1;
                        } else {
                            $parent_index = count($tabs) - 1;
                            if (!!$onetopicweeks) {
                                // AMT-136: Here we are *not* going to increment the level of the tab,
                                // so that the weeks onetopic view only prints one level of tabs.
                                // Users can set the child tabs as child tabs, we will just ignore it.
                                // That way no content goes missing or is lost, but we don't have to
                                // completely rewrite the way that the tabs children are handled
                                // by the onetopic format settings.
                                $new_tab->level = 1;
                                $tabs[] = $new_tab;
                            } else {
                                // Default onetopic tabs rendering behavior.
                                // Child tabs inherit the name in the topic series.
                                // We don't want this for onetopic weeks display because
                                // it causes weeks to be children of weeks.
                                if (!is_array($tabs[$parent_index]->subtree)) { // If there is no subtree.
                                    $tabs[$parent_index]->subtree = array();
                                } else if (count($tabs[$parent_index]->subtree) == 0) { // Else if nothing in subtree.
                                    $tabs[$parent_index]->subtree[0] = clone($tabs[$parent_index]);
                                    $tabs[$parent_index]->subtree[0]->id .= '_index';
                                    $parent_section = $sections[$section-1];
                                    $parentformat_options = course_get_format($course)->get_format_options($parent_section);
                                    if ($parentformat_options['firsttabtext']) {
                                        $firsttab_text = $parentformat_options['firsttabtext'];
                                    } else {
                                        $firsttab_text = get_string('index', 'format_onetopic');
                                    }
                                    $tabs[$parent_index]->subtree[0]->text = '<div class="tab_content tab_initial tab_level_' . $level . '">' . $firsttab_text. "</div>";
                                    $tabs[$parent_index]->subtree[0]->level = 2;

                                    if($displaysection == $section - 1) {
                                        $tabs[$parent_index]->subtree[0]->selected = true;
                                    }
                                }
                                $new_tab->level = 2;
                                $tabs[$parent_index]->subtree[] = $new_tab;
                            }
                        }
                    } else {
                        $tabs[] = $new_tab;
                    }

                    //Init move section list***************************************************************************
                    if ($can_move) {
                        if ($section > 0) { // Move section
                            $baseurl = course_get_url($course, $displaysection);
                            $baseurl->param('sesskey', sesskey());

                            $url = clone($baseurl);

                            $url->param('move', $section - $displaysection);

                            //ToDo: For new feature: subtabs. It is not implemented yet
                            /*
                            $strsubtopictoright = get_string('subtopictoright', 'format_onetopic');
                            $url = new moodle_url('/course/view.php', array('id' => $course->id, 'subtopicmove' => 'right', 'subtopic' => $section));
                            $icon = $this->output->pix_icon('t/right', $strsubtopictoright);
                            $subtopic_move = html_writer::link($url, $icon.get_accesshide($strsubtopictoright), array('class' => 'subtopic-increase-sections'));


                            if ($displaysection != $section) {
                                $move_list_html .= html_writer::tag('li', $subtopic_move . html_writer::link($url, $sectionname));
                               }
                            else {
                                $move_list_html .= html_writer::tag('li', $subtopic_move . $sectionname);
                            }
                            */

                            //Define class from sublevels in order to move a margen in the left. Not apply if it is the first element (condition !empty($move_list_html)) because the first element can't be a sublevel
                            $li_class = '';
                            if (is_array($format_options) && isset($format_options['level']) && $format_options['level'] > 0 && !empty($move_list_html)) {
                                $li_class = 'sublevel';
                            }

                            if ($displaysection != $section) {
                                $move_list_html .= html_writer::tag('li', html_writer::link($url, $sectionname), array('class' => $li_class));
                               }
                            else {
                                $move_list_html .= html_writer::tag('li', $sectionname, array('class' => $li_class));
                            }
                        }
                    }
                    //End move section list***************************************************************************
                }
            }

            $section++;
        }

        // Title with section navigation links.
        $sectionnavlinks = $this->get_nav_links($course, $sections, $displaysection);
        $sectiontitle = '';

        if (!$course->hidetabsbar && count($tabs[0]) > 0) {
            // Adds the add and remove section buttons.
            if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {
                // Increase number of sections.
                $straddsection = get_string('increasesections', 'moodle');
                $url = new moodle_url('/course/changenumsections.php',
                    array('courseid' => $course->id,
                        'increase' => true,
                        'sesskey' => sesskey(),
                        'insertsection' => 0));
                $icon = $this->output->pix_icon('t/switch_plus', $straddsection);
                $tabs[] = new tabobject("tab_topic_add", $url, $icon, s($straddsection));
                if (!isset($course->numsections)) {
                    $course->numsections = $this->gen_numsections();
                }

            }

            // Here's where the tabs are rendered. All of the levels of tabs are passed.
            $sectiontitle .= $OUTPUT->tabtree($tabs, "tab_topic_" . $displaysection, $inactive_tabs);//print_tabs($tabs, "tab_topic_" . $displaysection, $inactive_tabs, $active_tabs, true);
        }

        echo $sectiontitle;

        if (!$sections[$displaysection]->uservisible && !$canviewhidden) {
            if (!$course->hiddensections) {
                //Not used more, is controled in /course/view.php
            }
            // Can't view this section.
        }
        else {

            if ($course->realcoursedisplay != COURSE_DISPLAY_MULTIPAGE || $displaysection !== 0) {
                // Now the list of sections..
                echo $this->start_section_list();

                // The requested section page.
                $thissection = $sections[$displaysection];
                echo $this->section_header($thissection, $course, true);
                // Show completion help icon.
                $completioninfo = new completion_info($course);
                echo $completioninfo->display_help_icon();

                if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_NOT) {
                    echo $this->courserenderer->course_section_cm_list($course, $thissection, $displaysection);
                }
                else if ($this->_course->templatetopic == format_onetopic::TEMPLATETOPIC_LIST) {
                    echo $this->custom_course_section_cm_list($course, $thissection, $displaysection);
                }

                echo $this->courserenderer->course_section_add_cm_control($course, $displaysection, $displaysection);
                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        // Display section bottom navigation.
        $sectionbottomnav = '';
        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        $sectionbottomnav .= html_writer::end_tag('div');
        echo $sectionbottomnav;

        // close single-section div.
        echo html_writer::end_tag('div');

        if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $context)) {

            echo '<br class="utilities-separator" />';
            print_collapsible_region_start('move-list-box clearfix collapsible mform', 'course_format_onetopic_config_movesection', get_string('utilities', 'format_onetopic'), '', true);


            //Move controls
            if ($can_move && !empty($move_list_html)) {
                echo html_writer::start_div("form-item clearfix");
                    echo html_writer::start_div("form-label");
                        echo html_writer::tag('label', get_string('movesectionto', 'format_onetopic'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-setting");
                        echo html_writer::tag('ul', $move_list_html, array('class' => 'move-list'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-description");
                        echo html_writer::tag('p', get_string('movesectionto_help', 'format_onetopic'));
                    echo html_writer::end_div();
                echo html_writer::end_div();
            }

            $baseurl = course_get_url($course, $displaysection);
            $baseurl->param('sesskey', sesskey());

            $url = clone($baseurl);

            global $USER, $OUTPUT;
            if (isset($USER->onetopic_da[$course->id]) && $USER->onetopic_da[$course->id]) {
                $url->param('onetopic_da', 0);
                $text_button_disableajax = get_string('enable', 'format_onetopic');
            }
            else {
                $url->param('onetopic_da', 1);
                $text_button_disableajax = get_string('disable', 'format_onetopic');
            }

            echo html_writer::start_div("form-item clearfix");
                echo html_writer::start_div("form-label");
                    echo html_writer::tag('label', get_string('disableajax', 'format_onetopic'));
                echo html_writer::end_div();
                echo html_writer::start_div("form-setting");
                    echo html_writer::link($url, $text_button_disableajax);
                echo html_writer::end_div();
                echo html_writer::start_div("form-description");
                    echo html_writer::tag('p', get_string('disableajax_help', 'format_onetopic'));
                echo html_writer::end_div();
            echo html_writer::end_div();

            //Duplicate current section option
            if (has_capability('moodle/course:manageactivities', $context)) {
                $url_duplicate = new moodle_url('/course/format/onetopic/duplicate.php', array('courseid' => $course->id, 'section' => $displaysection, 'sesskey' => sesskey()));

                $link = new action_link($url_duplicate, get_string('duplicate', 'format_onetopic'));
                $link->add_action(new confirm_action(get_string('duplicate_confirm', 'format_onetopic'), null, get_string('duplicate', 'format_onetopic')));

                echo html_writer::start_div("form-item clearfix");
                    echo html_writer::start_div("form-label");
                        echo html_writer::tag('label', get_string('duplicatesection', 'format_onetopic'));
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-setting");
                        echo $this->render($link);
                    echo html_writer::end_div();
                    echo html_writer::start_div("form-description");
                        echo html_writer::tag('p', get_string('duplicatesection_help', 'format_onetopic'));
                    echo html_writer::end_div();
                echo html_writer::end_div();
            }

            print_collapsible_region_end();
        }
    }

    /**
     * Generate the display of the header part of a section before
     * course modules are included.
     * (Repurposed from format_onetopic_renderer.)
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @param bool $onsectionpage true if being printed on a single-section page
     * @param int $sectionreturn The section to return to after an action
     * @return string HTML to output.
     */
    protected function section_header($section, $course, $onsectionpage, $sectionreturn=null) {
        global $PAGE;

        $o = '';
        $currenttext = '';
        $sectionstyle = '';

        if ($section->section != 0) {
            // Only in the non-general sections.
            if (!$section->visible) {
                $sectionstyle = ' hidden';
            } else if (course_get_format($course)->is_section_current($section)) {
                $sectionstyle = ' current';
            }
        }

        $o.= html_writer::start_tag('li', array('id' => 'section-'.$section->section,
                'class' => 'section main clearfix'.$sectionstyle, 'role'=>'region',
                'aria-label'=> get_section_name($course, $section)));

        $leftcontent = $this->section_left_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $leftcontent, array('class' => 'left side'));

        $rightcontent = $this->section_right_content($section, $course, $onsectionpage);
        $o.= html_writer::tag('div', $rightcontent, array('class' => 'right side'));
        $o.= html_writer::start_tag('div', array('class' => 'content'));

        $classes = ' accesshide';

        $sectionname = html_writer::tag('span', $this->section_title($section, $course));
        $o.= $this->output->heading($sectionname, 3, 'sectionname' . $classes);

        $o.= html_writer::start_tag('div', array('class' => 'summary'));
        $o.= $this->build_summary_text($section, $course); // AMT-136
        $o.= html_writer::end_tag('div');

        $context = context_course::instance($course->id);
        $o .= $this->section_availability_message($section,
            has_capability('moodle/course:viewhiddensections', $context));

        return $o;
    }

    /**
     * Generate html for a section summary text
     * (Modification of format_onetopic_renderer::format_summary_text().)
     *
     * @param stdClass $section The course_section entry from DB
     * @return string HTML to output.
     */
    protected function build_summary_text($section, $course) {
        if ($course->templatetopic != format_onetopic::TEMPLATETOPIC_NOT) {
            $section->summary = $this->replace_resources($section);
        }
        return \format_section_renderer_base::format_summary_text($section); // AMT-136
    }
    /**
     * Generate the number of sections in a course.
     */
    protected function gen_numsections() {
        global $DB, $COURSE;
        $numsections = $DB->get_field('course_sections', 'MAX(section)', array('course' => $COURSE->id), MUST_EXIST);
        return $numsections;
    }
    /**
     * Process the template.
     *
     * @param stdClass $section The course_section entry from DB
     * @return string HTML to output.
     */
    private function replace_resources ($section) {

        global $CFG, $USER, $PAGE;
        static $initialised;
        static $groupbuttons;
        static $groupbuttonslink;
        static $strunreadpostsone;
        static $usetracking;
        static $groupings;

        $course = $this->_course;
        $completioninfo = new completion_info($course);

        if (!isset($initialised)) {
            $groupbuttons     = ($course->groupmode || (!$course->groupmodeforce));
            $groupbuttonslink = (!$course->groupmodeforce);
            include_once($CFG->dirroot.'/mod/forum/lib.php');
            if ($usetracking = forum_tp_can_track_forums()) {
                $strunreadpostsone = get_string('unreadpostsone', 'forum');
            }
            $initialised = true;
        }

        $labelformatoptions = new stdclass();
        $labelformatoptions->noclean = true;

        // Casting $course->modinfo to string prevents one notice when the field is null.
        $modinfo = $this->_format_data->modinfo;

        $summary = $section->summary;

        $htmlresource = '';
        $htmlmore     = '';
        if (!empty($section->sequence)) {
            $sectionmods = explode(",", $section->sequence);
            $objreplace = new format_onetopic_replace_regularexpression();

            $showyuidialogue = false;
            foreach ($sectionmods as $modnumber) {

                if (empty($this->_format_data->mods[$modnumber])) {
                    continue;
                }

                $mod = $this->_format_data->mods[$modnumber];

                if ($mod->modname == "label") {
                    continue;
                }

                $instancename = format_string($modinfo->cms[$modnumber]->name, true, $course->id);

                // Display the link to the module (or do nothing if module has no url).
                $cmname = $this->courserenderer->course_section_cm_name($mod);

                if (!empty($cmname)) {
                    $cmname = str_replace('<div ', '<span ', $cmname);
                    $cmname = str_replace('</div>', '</span>', $cmname);
                    $htmlresource = $cmname . $mod->afterlink;
                } else {
                    $htmlresource = '';
                }

                // If there is content but NO link (eg label), then display the
                // content here (BEFORE any icons). In this case cons must be
                // displayed after the content so that it makes more sense visually
                // and for accessibility reasons, e.g. if you have a one-line label
                // it should work similarly (at least in terms of ordering) to an
                // activity.
                $contentpart = $this->courserenderer->course_section_cm_text($mod);

                $url = $mod->url;
                if (!empty($url)) {
                    // If there is content AND a link, then display the content here
                    // (AFTER any icons). Otherwise it was displayed before.
                    $contentpart = str_replace('<div ', '<span ', $contentpart);
                    $contentpart = str_replace('</div>', '</span>', $contentpart);
                    $htmlresource .= $contentpart;
                }

                $availabilitytext = trim($this->courserenderer->course_section_cm_availability($mod));
                if (!empty($availabilitytext)) {
                    $uniqueid = 'format_onetopic_winfo_' . time() . '-' . rand(0, 1000);
                    $htmlresource .= '<span class="iconhelp" data-infoid="' . $uniqueid . '">' .
                                        $this->output->pix_icon('a/help', get_string('help')) .
                                     '</span>';

                    $htmlmore .= '<div id="' . $uniqueid . '" class="availability_info_box" style="display: none;">' .
                    $availabilitytext . '</div>';

                    $showyuidialogue = true;
                }

                // Replace the link in pattern: [[resource name]].
                $objreplace->_string_replace = $htmlresource;
                $objreplace->_string_search = $instancename;
                 $newsummary = preg_replace_callback("/(\[\[)(([<][^>]*>)*)((" . preg_quote($objreplace->_string_search, '/') .
                    ")(:?))([^\]]*)\]\]/i", array($objreplace, "replace_tag_in_expresion"), $summary);

                if ($newsummary != $summary) {
                    unset($this->_format_data->mods[$modnumber]);
                }

                $summary = $newsummary;
            }

            if ($showyuidialogue) {
                $PAGE->requires->yui_module('moodle-core-notification-dialogue', 'M.course.format.dialogueinit');
            }

        }

        if ($this->_course->templatetopic_icons == 0) {
            $summary = '<span class="onetopic_hideicons">' . $summary . '</span>';
        }

        return $summary . $htmlmore;
    }

}
