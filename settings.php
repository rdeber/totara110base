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
 * Setttings for component 'theme_totara110base'
 *
 * @package   theme_totara110base
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__).'/admin_settings.php');

defined('MOODLE_INTERNAL') || die;

// Define tab constants
if (!defined('THEME_totara110base_TAB_DESIGN')) {
    /**
     * THEME_totara110base_TAB_DESIGN - Order/link reference for design tab.
     */
    define('THEME_totara110base_TAB_DESIGN', 0);
    /**
     * THEME_totara110base_TAB_FRONTPAGE - Order/link reference for front page tab.
     */
    define('THEME_totara110base_TAB_FRONTPAGE', 1);
    /**
     * THEME_totara110base_TAB_UI - Order/link reference for UI tab.
     */
    define('THEME_totara110base_TAB_UI', 2);
    /**
     * THEME_totara110base_TAB_ADVANCED - Order/link reference for advanced tab.
     */
    define('THEME_totara110base_TAB_ADVANCED', 3);
    /**
     * THEME_TOTARA110BASE_TAB_ALERTS - Order/link reference for alert tab.
     */
    define('THEME_TOTARA110BASE_TAB_ALERTS', 4);

}

if ($ADMIN->fulltree) {

    $themename = 'theme_totara110base';

    $name = $themename .'/tabs';
    $tabs = new totara110base_admin_setting_tabs($name, $settings->name, $reload);
    $tabs->addtab(THEME_totara110base_TAB_DESIGN, get_string('tab-design', $themename));
    $tabs->addtab(THEME_totara110base_TAB_FRONTPAGE, get_string('tab-frontpage', $themename));
    $tabs->addtab(THEME_totara110base_TAB_UI, get_string('tab-ui', $themename));
    $tabs->addtab(THEME_totara110base_TAB_ADVANCED, get_string('tab-advanced', $themename));
    $tabs->addtab(THEME_TOTARA110BASE_TAB_ALERTS, get_string('tab-alerts', $themename));

    $settings->add($tabs);

    $tab = $tabs->get_setting();

    if ($tab === THEME_totara110base_TAB_DESIGN) {

        // Control Section Heading.
        $name = $themename .'/designtabheading';
        $title = get_string('tab-design-title', $themename);
        $description = html_writer::tag('p', get_string('tab-design-description', $themename));
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        // Color setting.
        $name = 'theme_totara110base/themecolor';
        $title = get_string('themecolor', 'theme_totara110base');
        $description = get_string('themecolordesc', 'theme_totara110base');
        $setting = new admin_setting_configselect($name, $title, $description, 'blueorange-theme', array(
            ' ' => get_string('themecolor-default', 'theme_totara110base'),
            'neutral-theme' => get_string('themecolor-neutral', 'theme_totara110base'),
            'red-theme' => get_string('themecolor-red', 'theme_totara110base'),
            'blue-theme' => get_string('themecolor-blue', 'theme_totara110base'),
            'green-theme' => get_string('themecolor-green', 'theme_totara110base'),
            'purple-theme' => get_string('themecolor-purple', 'theme_totara110base'),
            'graygreen-theme' => get_string('themecolor-graygreen', 'theme_totara110base'),
            'grayred-theme' => get_string('themecolor-grayred', 'theme_totara110base'),
            'bluegold-theme' => get_string('themecolor-bluegold', 'theme_totara110base'),
            'blueorange-theme' => get_string('themecolor-blueorange', 'theme_totara110base'),
            'bluered-theme' => get_string('themecolor-bluered', 'theme_totara110base'),
            'blackgold-theme' => get_string('themecolor-blackgold', 'theme_totara110base'),
            'blackyellow-theme' => get_string('themecolor-blackyellow', 'theme_totara110base'),
            'purplegold-theme' => get_string('themecolor-purplegold', 'theme_totara110base'),
            'greengold-theme' => get_string('themecolor-greengold', 'theme_totara110base'),
            'maroongold-theme' => get_string('themecolor-maroongold', 'theme_totara110base'),
            'earth-theme' => get_string('themecolor-earth', 'theme_totara110base'),
            ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Logo file setting.
        $name = 'theme_totara110base/logo';
        $title = get_string('logo', 'theme_totara110base');
        $description = get_string('logodesc', 'theme_totara110base');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Favicon file setting.
        $name = 'theme_totara110base/favicon';
        $title = get_string('favicon', 'theme_totara110base');
        $description = get_string('favicondesc', 'theme_totara110base');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'favicon');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Social media icons textarea.
        $name = 'theme_totara110base/socialmediaicons';
        $title = get_string('socialmediaicons', 'theme_totara110base');
        $description = get_string('socialmediaiconsdesc', 'theme_totara110base');
        $default = "facebook|http://www.facebook.com\n";
        $default .= "twitter|http://twitter.com\n";
        $default .= "vimeo|https://vimeo.com\n";
        $default .= "linkedin|http://www.linkedin.com\n";
        $default .= "rss|#";
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

    } else if ($tab == THEME_totara110base_TAB_FRONTPAGE) {

        // Front page section heading.
        $name = $themename .'/frontpagetabheading';
        $title = get_string('tab-frontpage-title', $themename);
        $description = html_writer::tag('p', get_string('tab-frontpage-description', $themename));
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        // Image Pause setting.
        $name = 'theme_totara110base/pausesetting';
        $title = get_string('pausesetting', $themename);
        $description = html_writer::tag('p', get_string('pausesetting_desc', $themename));
        $pausesetting = get_config($themename, 'pausesetting');
        if (empty($pausesetting)) {
            set_config('pausesetting', '4', $themename);
        }
        $setting = new admin_setting_configselect($name, $title, $description, '4', array(
            '0' => '0',
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
            '7' => '7',
            '8' => '8',
            '9' => '9',
            '10' => '10',
            '15' => '15',
            '20' => '20',
            '30' => '30',
            '40' => '40',
            ));

        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Loop over 4 banner image settings.
        for ($i = 1; $i <= 4; $i++) {

            // Slider image header heading.
            $name = $themename .'/sliderheading'.$i;
            $title = get_string('sliderheading'.$i, $themename);
            $description = html_writer::tag('div', get_string('sliderheading'.$i.'desc', $themename));
            $setting = new admin_setting_heading($name, $title, $description);
            $settings->add($setting);

            // Slider image file setting.
            $name = 'theme_totara110base/sliderimage'.$i;
            $title = get_string('sliderimage'.$i, 'theme_totara110base');
            $description = get_string('sliderimage'.$i.'desc', 'theme_totara110base');
            $setting = new admin_setting_configstoredfile($name, $title, $description, 'sliderimage'.$i);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Slider header setting.
            $name = 'theme_totara110base/sliderheader'.$i;
            $title = get_string('sliderheader'.$i, 'theme_totara110base');
            $description = get_string('sliderheader'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Slider text setting.
            $name = 'theme_totara110base/slidertext'.$i;
            $title = get_string('slidertext'.$i, 'theme_totara110base');
            $description = get_string('slidertext'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Slider button link setting.
            $name = 'theme_totara110base/sliderbuttonlink'.$i;
            $title = get_string('sliderbuttonlink'.$i, 'theme_totara110base');
            $description = get_string('sliderbuttonlink'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Slider 1 button label setting.
            $name = 'theme_totara110base/sliderbuttonlabel'.$i;
            $title = get_string('sliderbuttonlabel'.$i, 'theme_totara110base');
            $description = get_string('sliderbuttonlabel'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);
        }

        // Video settings section heading.
        $name = $themename .'/frontpagetabvideoheading';
        $title = get_string('tab-frontpage-video', $themename);
        $description = html_writer::tag('p', get_string('tab-frontpage-video-description', $themename));
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        // Video banner source mp4.
        $name = 'theme_totara110base/videobackgroundmp4';
        $title = get_string('videobackgroundmp4', 'theme_totara110base');
        $description = get_string('videobackgroundmp4_desc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Video banner source webm.
        $name = 'theme_totara110base/videobackgroundwebm';
        $title = get_string('videobackgroundwebm', 'theme_totara110base');
        $description = get_string('videobackgroundwebm_desc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Video banner source ogg.
        $name = 'theme_totara110base/videobackgroundogg';
        $title = get_string('videobackgroundogg', 'theme_totara110base');
        $description = html_writer::empty_tag('hr').get_string('videobackgroundogg_desc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Video background static image.
        $name = 'theme_totara110base/videoimage';
        $title = get_string('videoimage', 'theme_totara110base');
        $description = get_string('videoimage_desc', 'theme_totara110base');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'videoimage');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Video banner header setting.
        $name = 'theme_totara110base/videoheader';
        $title = get_string('videoheader', 'theme_totara110base');
        $description = get_string('videoheader_desc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Video banner text setting.
        $name = 'theme_totara110base/videotext';
        $title = get_string('videotext', 'theme_totara110base');
        $description = get_string('videotext_desc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Subsection settings section heading.
        $name = $themename .'/subsectionsheading';
        $title = get_string('tab-frontpage-subsections', $themename);
        $description = html_writer::tag('p', get_string('tab-frontpage-subsections-description', $themename));
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        // Loop over 3 banner image settings.
        for ($i = 1; $i <= 3; $i++) {

            // Subsection settings section heading.
            $name = $themename .'/subsectionheading'.$i;
            $title = get_string('subsectionheading'.$i, $themename);
            $description = html_writer::tag('div', get_string('subsectionheading'.$i.'desc', $themename));
            $setting = new admin_setting_heading($name, $title, $description);
            $settings->add($setting);

            // Subsection header setting.
            $name = 'theme_totara110base/subsectiontitle'.$i;
            $title = get_string('subsectiontitle'.$i, 'theme_totara110base');
            $description = get_string('subsectiontitle'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Subsection icon setting.
            $name = 'theme_totara110base/subsectionicon'.$i;
            $title = get_string('subsectionicon'.$i, 'theme_totara110base');
            $description = get_string('subsectionicon'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Subsection text setting.
            $name = 'theme_totara110base/subsectiondescription'.$i;
            $title = get_string('subsectiondescription'.$i, 'theme_totara110base');
            $description = get_string('subsectiondescription'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Subsection link setting.
            $name = 'theme_totara110base/subsectionlink'.$i;
            $title = get_string('subsectionlink'.$i, 'theme_totara110base');
            $description = get_string('subsectionlink'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

            // Slider header setting.
            $name = 'theme_totara110base/subsectionlabel'.$i;
            $title = get_string('subsectionlabel'.$i, 'theme_totara110base');
            $description = get_string('subsectionlabel'.$i.'desc', 'theme_totara110base');
            $default = '';
            $setting = new admin_setting_configtext($name, $title, $description, $default);
            $setting->set_updatedcallback('theme_reset_all_caches');
            $settings->add($setting);

        }

        // Enhanced front-page course widgets.
        $name = 'theme_totara110base/enhancedcoursewidgets';
        $title = get_string('enhancedcoursewidgets', 'theme_totara110base');
        $description = get_string('enhancedcoursewidgets_desc', 'theme_totara110base');
        $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Enhanced front-page course widgets summary word limit.
        $name = 'theme_totara110base/enhancedcoursewidgetsummarylimit';
        $title = get_string('enhancedcoursewidgetsummarylimit', 'theme_totara110base');
        $description = get_string('enhancedcoursewidgetsummarylimit_desc', 'theme_totara110base');
        $setting = new admin_setting_configtext($name, $title, $description, 0);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Front page demo mode.
        $name = 'theme_totara110base/demomode';
        $title = get_string('demomode', 'theme_totara110base');
        $description = get_string('demomodedesc', 'theme_totara110base');
        $setting = new admin_setting_configselect($name, $title, $description, 'on', array(
            'on' => get_string('demomodeon', 'theme_totara110base'),
            'off' => get_string('demomodeoff', 'theme_totara110base'),
            ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

    } else if ($tab == THEME_totara110base_TAB_UI) {

        // UI section heading.
        $name = $themename .'/uitabheading';
        $title = get_string('tab-ui-title', $themename);
        $description = html_writer::tag('p', get_string('tab-ui-description', $themename));
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        // Replace img icons with FA icons.
        $name = 'theme_totara110base/replaceimgicons';
        $title = get_string('replaceimgicons', 'theme_totara110base');
        $description = get_string('replaceimgicons_desc', 'theme_totara110base');
        $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Custom Course Category AJAX.
        $name = 'theme_totara110base/coursecatajax';
        $title = get_string('coursecatajax', 'theme_totara110base');
        $description = get_string('coursecatajax_desc', 'theme_totara110base');
        $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Sidebar block region alignment setting.
        $name = 'theme_totara110base/sidebarblockregionalignment';
        $title = get_string('sidebarblockregionalignment', 'theme_totara110base');
        $description = get_string('sidebarblockregionalignmentdesc', 'theme_totara110base');
        $setting = new admin_setting_configselect($name, $title, $description, 'right', array(
            'right' => get_string('sidebarblockregionalignmentright', 'theme_totara110base'),
            'left' => get_string('sidebarblockregionalignmentleft', 'theme_totara110base')
        ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Sidebar block region button display setting.
        $name = 'theme_totara110base/sidebarblockregionbuttontype';
        $title = get_string('sidebarblockregionbuttontype', 'theme_totara110base');
        $description = get_string('sidebarblockregionbuttontypedesc', 'theme_totara110base');
        $setting = new admin_setting_configselect($name, $title, $description, 'icontext', array(
            'icontext' => get_string('sidebarblockregionbuttontypeicontext', 'theme_totara110base'),
            'icon' => get_string('sidebarblockregionbuttontypeicon', 'theme_totara110base'),
            'text' => get_string('sidebarblockregionbuttontypetext', 'theme_totara110base')
        ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Sidebar block region button display setting.
        $name = 'theme_totara110base/onetopicstyle';
        $title = get_string('onetopicstyle', 'theme_totara110base');
        $description = get_string('onetopicstyledesc', 'theme_totara110base');
        $setting = new admin_setting_configselect($name, $title, $description, '', array(
            '' => get_string('onetopicstylenone', 'theme_totara110base'),
            'onetopictabs' => get_string('onetopicstyleenhanced', 'theme_totara110base'),
            'onetopicvertical' => get_string('onetopicstylevertical', 'theme_totara110base')
        ));
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Display weeks of course (as in the weeks course format) instead of
        // topic names when using the onetopic course format.
        $name = 'theme_totara110base/onetopicweeks';
        $title = get_string('onetopicweeks', 'theme_totara110base');
        $description = get_string('onetopicweeksdesc', 'theme_totara110base');
        $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Grid course format enhanced style setting.
        $name = 'theme_totara110base/gridstyle';
        $title = get_string('gridstyle', 'theme_totara110base');
        $description = get_string('gridstyledesc', 'theme_totara110base');
        $setting = new admin_setting_configcheckbox($name, $title, $description, 0);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

    } else if ($tab == THEME_totara110base_TAB_ADVANCED) {

        // Advanced section heading.
        $name = $themename .'/advancedtabheading';
        $title = get_string('tab-advanced-title', $themename);
        $description = html_writer::tag('p', get_string('tab-advanced-description', $themename));
        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        // Custom CSS file overrides
        $name = 'theme_totara110base/overridecssfile';
        $title = get_string('overridecssfile', 'theme_totara110base');
        $description = get_string('overridecssfiledesc', 'theme_totara110base');
        $setting = new admin_setting_configstoredfile($name, $title, $description, 'overridecssfile');
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Custom CSS overrides.
        $name = 'theme_totara110base/overridecss';
        $title = get_string('overridecss', 'theme_totara110base');
        $description = get_string('overridecssdesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

    } else if ($tab == THEME_TOTARA110BASE_TAB_ALERTS) {
        // Alerts section heading.
        $name = $themename.'/alertstabheading';
        $title = get_string('tab-alert-title', 'theme_totara110base');
        $description = get_string('alertsdesc', 'theme_totara110base');

        $setting = new admin_setting_heading($name, $title, $description);
        $settings->add($setting);

        $information = get_string('alertinfodesc', 'theme_totara110base');
        // This is the descriptor for alert one.
        $name = 'theme_totara110base/alert1info';
        $heading = get_string('alert1', 'theme_totara110base');
        $setting = new admin_setting_heading($name, $heading, $information);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Enable alert.
        $name = 'theme_totara110base/enablealert';
        $title = get_string('enablealert', 'theme_totara110base');
        $description = get_string('enablealertdesc', 'theme_totara110base');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert type.
        $name = 'theme_totara110base/alert1type';
        $title = get_string('alerttype', 'theme_totara110base');
        $description = get_string('alerttypedesc', 'theme_totara110base');
        $alertinfo = get_string('alert_info', 'theme_totara110base');
        $alertwarning = get_string('alert_warning', 'theme_totara110base');
        $alertgeneral = get_string('alert_general', 'theme_totara110base');
        $default = 'info';
        $choices = array('info' => $alertinfo, 'danger' => $alertwarning, 'success' => $alertgeneral);
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert title.
        $name = 'theme_totara110base/alert1title';
        $title = get_string('alerttitle', 'theme_totara110base');
        $description = get_string('alerttitledesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert text.
        $name = 'theme_totara110base/alert1text';
        $title = get_string('alerttext', 'theme_totara110base');
        $description = get_string('alerttextdesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // This is the descriptor for alert two.
        $name = 'theme_totara110base/alert2info';
        $heading = get_string('alert2', 'theme_totara110base');
        $setting = new admin_setting_heading($name, $heading, $information);
        $settings->add($setting);

        // Enable alert.
        $name = 'theme_totara110base/enable2alert';
        $title = get_string('enablealert', 'theme_totara110base');
        $description = get_string('enablealertdesc', 'theme_totara110base');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert type.
        $name = 'theme_totara110base/alert2type';
        $title = get_string('alerttype', 'theme_totara110base');
        $description = get_string('alerttypedesc', 'theme_totara110base');
        $alertinfo = get_string('alert_info', 'theme_totara110base');
        $alertwarning = get_string('alert_warning', 'theme_totara110base');
        $alertgeneral = get_string('alert_general', 'theme_totara110base');
        $default = 'info';
        $choices = array('info' => $alertinfo, 'danger' => $alertwarning, 'success' => $alertgeneral);
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert title.
        $name = 'theme_totara110base/alert2title';
        $title = get_string('alerttitle', 'theme_totara110base');
        $description = get_string('alerttitledesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert text.
        $name = 'theme_totara110base/alert2text';
        $title = get_string('alerttext', 'theme_totara110base');
        $description = get_string('alerttextdesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // This is the descriptor for alert three.
        $name = 'theme_totara110base/alert3info';
        $heading = get_string('alert3', 'theme_totara110base');
        $setting = new admin_setting_heading($name, $heading, $information);
        $settings->add($setting);

        // Enable alert.
        $name = 'theme_totara110base/enable3alert';
        $title = get_string('enablealert', 'theme_totara110base');
        $description = get_string('enablealertdesc', 'theme_totara110base');
        $default = false;
        $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert type.
        $name = 'theme_totara110base/alert3type';
        $title = get_string('alerttype', 'theme_totara110base');
        $description = get_string('alerttypedesc', 'theme_totara110base');
        $alertinfo = get_string('alert_info', 'theme_totara110base');
        $alertwarning = get_string('alert_warning', 'theme_totara110base');
        $alertgeneral = get_string('alert_general', 'theme_totara110base');
        $default = 'info';
        $choices = array('info' => $alertinfo, 'danger' => $alertwarning, 'success' => $alertgeneral);
        $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert title.
        $name = 'theme_totara110base/alert3title';
        $title = get_string('alerttitle', 'theme_totara110base');
        $description = get_string('alerttitledesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

        // Alert text.
        $name = 'theme_totara110base/alert3text';
        $title = get_string('alerttext', 'theme_totara110base');
        $description = get_string('alerttextdesc', 'theme_totara110base');
        $default = '';
        $setting = new admin_setting_configtextarea($name, $title, $description, $default);
        $setting->set_updatedcallback('theme_reset_all_caches');
        $settings->add($setting);

    }

}
